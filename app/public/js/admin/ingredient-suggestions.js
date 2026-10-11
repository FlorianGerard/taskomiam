document.addEventListener("DOMContentLoaded", () => {
    const input = document.querySelector("[data-ingredient-search]");

    if (!input) {
        return;
    }

    let timeout;

    input.addEventListener("input", () => {
        clearTimeout(timeout);

        const query = input.value.trim();

        if (query.length < 2) {
            return;
        }

        timeout = setTimeout(() => {
            const controller = new AbortController();

            searchIngredients(query, controller.signal)
                .then((ingredients) => {
                    clearSuggestions();
                    displaySuggestions(ingredients);
                })
                .catch((error) => {
                    console.error(
                        "Erreur lors de la recherche des ingrédients :",
                        error,
                    );
                    clearSuggestions();
                });
        }, 300);
    });
});

async function searchIngredients(query, signal) {
    const url = "/admin/ingredients/suggestions/" + encodeURIComponent(query);

    const response = await fetch(url, { signal });

    if (!response.ok) {
        throw new Error("Erreur HTTP : " + response.status);
    }

    const ingredients = await response.json();

    console.log(ingredients);

    return ingredients;
}

function displaySuggestions(ingredients) {
    const suggestionsContainer = document.querySelector(
        "#ingredient-suggestions",
    );

    if (!suggestionsContainer) {
        return;
    }

    suggestionsContainer.innerHTML = "<div class='suggestion-container'></div>";

    ingredients.forEach((ingredient) => {
        const suggestionItem = document.createElement("div");
        suggestionItem.textContent = ingredient.name;
        suggestionItem.classList.add(
            "list-group-item",
            "list-group-item-action",
        );
        suggestionItem.addEventListener("click", () =>
            selectSuggestion(ingredient),
        );
        suggestionsContainer
            .querySelector(".suggestion-container")
            .appendChild(suggestionItem);
    });
}

function clearSuggestions() {
    const suggestionsContainer = document.querySelector(
        "#ingredient-suggestions",
    );

    if (!suggestionsContainer) {
        return;
    }

    suggestionsContainer.innerHTML = "";
}

function selectSuggestion(ingredient) {
    const input = document.querySelector("[data-ingredient-search]");

    if (!input) {
        return;
    }

    input.value = ingredient.name;
    clearSuggestions();
}
