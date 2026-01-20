<?php
include('../connection.php');

/**
 * Class VehicleManager
 * Handles inserting bike / car details for a flat number.
 */
class VehicleManager
{
    public function addVehical($flatno, $vehicles)
    {
        global $connection;

        if (!$connection) {
            return json_encode([
                'status'  => 'error',
                'message' => 'Database connection failed'
            ]);
        }

        $errors = [];

        foreach ($vehicles as $value) {
            $vehical    = $value['vehical'] ?? null;
            $imageName  = $value['image']['name'] ?? null;
            $imageTmp   = $value['image']['tmp_name'] ?? null;
            $type       = $value['type'] ?? null;   // 0 = bike, 1 = car

            // prepare insert SQL depending on vehicle type
            if ($type == 0) {
                $sql_insert = "INSERT INTO `tbl_vehicles`
                               (`flat_number`, `bike`, `flag`)
                               VALUES (?, ?, ?)";
            } else {
                $sql_insert = "INSERT INTO `tbl_vehicles`
                               (`flat_number`, `car`, `flag`)
                               VALUES (?, ?, ?)";
            }

            $stmt_insert = $connection->prepare($sql_insert);
            if (!$stmt_insert) {
                $errors[] = "Prepare failed: " . $connection->error;
                continue;
            }

            $stmt_insert->bind_param('sss', $flatno, $vehical, $type);

            if ($stmt_insert->execute()) {
                // ✅ use $connection->insert_id for prepared statements
                $inserted_id = $connection->insert_id;

                // Only try image upload if a file is actually sent
                if (!empty($imageName) && is_uploaded_file($imageTmp)) {
                    $target_dir  = "../../assets/images/vehicalImage/";
                    if (!is_dir($target_dir)) {
                        mkdir($target_dir, 0777, true);
                    }

                    $ext          = pathinfo($imageName, PATHINFO_EXTENSION);
                    $unique_image = $inserted_id . '.' . $ext;
                    $target_file  = $target_dir . $unique_image;

                    if (move_uploaded_file($imageTmp, $target_file)) {
                        $sql_update = "UPDATE `tbl_vehicles` SET `image` = ? WHERE `id` = ?";
                        $stmt_update = $connection->prepare($sql_update);
                        if ($stmt_update) {
                            $stmt_update->bind_param('si', $unique_image, $inserted_id);
                            $stmt_update->execute();
                            $stmt_update->close();
                        } else {
                            $errors[] = "Update prepare failed: " . $connection->error;
                        }
                    } else {
                        $errors[] = "Failed to move uploaded file for vehicle: $vehical";
                    }
                }
            } else {
                // Capture MySQL error if insert fails
                $errors[] = "Insert failed for vehicle {$vehical}: " . $stmt_insert->error;
            }

            $stmt_insert->close();
        }

        if (empty($errors)) {
            return json_encode([
                'status'  => 'success',
                'message' => 'All vehicles added successfully'
            ]);
        }

        return json_encode([
            'status'  => 'error',
            'message' => 'Failed to add some vehicles',
            'errors'  => $errors
        ]);
    }
}
