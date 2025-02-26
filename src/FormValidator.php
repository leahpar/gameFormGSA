<?php
namespace App;

class FormValidator
{
    /**
     * Nettoie une chaîne d'entrée
     */
    public function sanitizeInput(string $input): string
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        return $input;
    }

    /**
     * Valide un email
     */
    public function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Valide un numéro de téléphone (format français simplifié)
     * Cette méthode peut être adaptée selon les besoins spécifiques
     */
    public function validatePhone(string $phone): bool
    {
        return strlen($phone) === 10 && preg_match('/^[0-9]*$/', $phone);
    }

    public function sanitizeEmail(string $email): string
    {
        return strtolower(filter_var($email, FILTER_SANITIZE_EMAIL));
    }

    /**
     * Vérifie que tous les champs requis sont présents et non vides
     */
    public function validateRequiredFields(array $data, array $requiredFields): bool
    {
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty(trim($data[$field]))) {
                return false;
            }
        }
        return true;
    }

    public function sanitizeTel(mixed $param)
    {
        $phone = preg_replace('/\D/', '', $param);
        $phone = preg_replace('/^33/', '0', $phone);
        return $phone;
    }

    /**
     * Valide un code postal français (5 chiffres)
     */
    public function validatePostalCode(string $postalCode): bool
    {
        return preg_match('/^\d{5}$/', $postalCode) === 1;
    }
    
    public function validateData(array $data)
    {
        return !$this->validateRequiredFields($data, ['nom', 'prenom', 'email', 'telephone', 'code_postal', 'travaux'])
            || !$this->validateEmail($data['email'])
            || !$this->validatePhone($data['telephone'])
            || !$this->validatePostalCode($data['code_postal']);
    }
}
