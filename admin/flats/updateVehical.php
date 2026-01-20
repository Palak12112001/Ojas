<?php
include('../connection.php');
include('insertVehical.php');
/**
 * Validate a vehicle number.
 *
 * @param string|null $vehicle The vehicle number to validate.
 * @param string $field The field name for the vehicle (e.g., 'bike' or 'car').
 * @return string|null An error message if validation fails, or null if validation passes.
 */
function validateVehicle($vehicle, $field)
{
    if ($vehicle === null || $vehicle === '') {
        return null;
    }

    if (strlen($vehicle) < 1 || strlen($vehicle) > 7) {
        return 'Invalid ' . ucfirst($field) . ' number. It should be between 1 and 7 characters.';
    }

    if (!preg_match('/^[a-zA-Z0-9]*$/', $vehicle)) {
        return 'Invalid ' . ucfirst($field) . ' number. It should be alphanumeric and up to 7 characters.';
    }

    return null;
}
/**
 * Update vehicle details in the database.
 *
 * @param mysqli $connection The database connection object.
 * @param array $id An array of vehicle IDs.
 * @param array $value An array of new values for the vehicles.
 * @param array $type An array indicating the type of vehicle (e.g., '0' for bike, '1' for car).
 * @param array|null $image An array of images associated with the vehicles.
 * @return array An associative array with the status of the update and any error messages.
 */
function updateVehicle($connection, $id, $value, $type,$flatno, $image = null )
{
    if (!$connection) {
        return ['status' => 'error', 'message' => 'Database connection failed'];
    }

    $errors   = [];
    $vehical  = []; // collect new vehicles to insert

    foreach ($value as $index => $val_value) {
        $val_id    = $id[$index]   ?? null;
        $val_type  = $type[$index] ?? null;
        $val_image = $image[$index] ?? null;

        // ---------- ADD NEW VEHICLE ----------
        if (empty($val_id)) {
            $vehical[] = [
                'vehical' => $val_value,
                'image'   => $val_image,
                'type'    => $val_type,
            ];
            continue; // no update—just collect for insert
        }

        // ---------- UPDATE EXISTING ----------
        if (!empty($val_value)) {
            // --- if a new image is provided ---
            if (!empty($val_image) && !empty($val_image['name'])) {
                // fetch old image name
                $stmt = $connection->prepare("SELECT image FROM tbl_vehicles WHERE id = ?");
                $stmt->bind_param('i', $val_id);
                $stmt->execute();
                $old_image_result = $stmt->get_result()->fetch_assoc();
                $stmt->close();

                if ($old_image_result && $old_image_result['image']) {
                    $old_path = "../../assets/images/vehicalImage/" . $old_image_result['image'];
                    if (file_exists($old_path)) unlink($old_path);
                }

                // move new image
                $target_dir  = "../../assets/images/vehicalImage/";
                if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
                $unique_name = $val_id . '.' . pathinfo($val_image['name'], PATHINFO_EXTENSION);
                $target_file = $target_dir . $unique_name;

                if (!move_uploaded_file($val_image['tmp_name'], $target_file)) {
                    $errors[] = "Failed to move uploaded file for ID $val_id";
                } else {
                    $stmt = $connection->prepare(
                        "UPDATE tbl_vehicles SET image = ? WHERE id = ? AND flag = ?"
                    );
                    $stmt->bind_param('sis', $unique_name, $val_id, $val_type);
                    if (!$stmt->execute()) {
                        $errors[] = "Image update failed for ID $val_id: " . $stmt->error;
                    }
                    $stmt->close();
                }
            }
        } else {
            // --- value empty => remove image ---
            $stmt = $connection->prepare("SELECT image FROM tbl_vehicles WHERE id = ?");
            $stmt->bind_param('i', $val_id);
            $stmt->execute();
            $old_image_result = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if ($old_image_result && $old_image_result['image']) {
                $old_path = "../../assets/images/vehicalImage/" . $old_image_result['image'];
                if (file_exists($old_path)) unlink($old_path);

                $stmt = $connection->prepare(
                    "UPDATE tbl_vehicles SET image = NULL WHERE id = ? AND flag = ?"
                );
                $stmt->bind_param('is', $val_id, $val_type);
                if (!$stmt->execute()) {
                    $errors[] = "Image delete failed for ID $val_id: " . $stmt->error;
                }
                $stmt->close();
            }
        }

        // --- update vehicle (bike/car) column ---
        $column = ($val_type === '0') ? 'bike' : 'car';
        $stmt   = $connection->prepare("UPDATE tbl_vehicles SET $column = ? WHERE id = ? AND flag = ?");
        $stmt->bind_param('sis', $val_value, $val_id, $val_type);
        if (!$stmt->execute()) {
            $errors[] = "Vehicle update failed for ID $val_id: " . $stmt->error;
        }
        $stmt->close();
    }

    // ---------- INSERT any new vehicles ----------
    if (!empty($vehical)) {
        $vehicleManager = new VehicleManager();
        foreach ($vehical as $v) {
            $vehicleManager->addVehical($flatno, [$v]);
        }
    }

    return empty($errors)
        ? ['status' => 'success', 'message' => 'All updates successful']
        : ['status' => 'error',   'message' => 'Some updates failed', 'errors' => $errors];
}

