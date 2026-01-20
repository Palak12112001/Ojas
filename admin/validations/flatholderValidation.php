<?php
/**
 * Validate a full name.
 *
 * @param string $fullName
 * @return string|null
 */
function validateFullName($fullName) {
    if (empty($fullName)) {
        return 'Full name is required.';
    } elseif (!preg_match('/^[a-zA-Z\s]+$/', $fullName)) {
        return 'Invalid full name. It must contain only letters and spaces.';
    }
    return null;
}

/**
 * Validate a phone number.
 *
 * @param string $phone
 * @return string|null
 */
function validatePhone($phone) {
    $phone = preg_replace('/[^0-9]/','', $phone); // Remove non-numeric characters
    if (empty($phone)) {
        return 'Phone number is required.';
    } elseif (strlen($phone) < 10 || strlen($phone) > 15) {
        return 'Invalid phone number. It must be between 10 and 15 digits.';
    }
    return null;
}

/**
 * Validate a vehicle number.
 *
 * @param string|null $vehicle
 * @param string $field
 * @return string|null
 */
function validateVehicle($vehicle, $field) {
    if ($vehicle === null || $vehicle === '') {
        return null;
    }

    if (!preg_match('/^[a-zA-Z0-9]{1,10}$/', $vehicle)) {
        return 'Invalid ' . ucfirst($field) . ' number. It should be alphanumeric and up to 10 characters.';
    }

    return null;
}

?>

