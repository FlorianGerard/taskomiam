<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class CsvReader
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {}

    public function parse(string $filePath): array
    {
        $rows = [];
        $headers = null;
        $lineNumber = 0;

        $handle = @fopen($filePath, 'r');

        if ($handle === false) {
            throw new \RuntimeException(
                sprintf('Impossible d\'ouvrir le fichier CSV : %s', $filePath)
            );
        }

        try {
            while (($data = fgetcsv($handle, 0, ',', '"', '')) !== false) {
                $lineNumber++;

                // Ignorer les lignes vides
                if ($data === [null]) {
                    $this->logger->warning('Ligne vide détectée dans le CSV', [
                        'file' => $filePath,
                        'line' => $lineNumber,
                    ]);

                    continue;
                }

                // Première ligne non vide = en-têtes
                if ($headers === null) {
                    $headers = $data;
                    continue;
                }

                // Vérifier le nombre de colonnes
                if (count($data) !== count($headers)) {
                    $this->logger->warning('Nombre de colonnes incorrect', [
                        'file' => $filePath,
                        'line' => $lineNumber,
                        'expected' => count($headers),
                        'actual' => count($data),
                    ]);

                    continue;
                }

                // Associer les valeurs aux en-têtes
                $rows[] = array_combine($headers, $data);
            }
        } finally {
            fclose($handle);
        }

        if ($headers === null) {
            throw new \RuntimeException(
                sprintf('Aucun en-tête trouvé dans le fichier CSV : %s', $filePath)
            );
        }

        return $rows;
    }
}
