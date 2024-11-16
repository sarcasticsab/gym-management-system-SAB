<?php include 'db_connect.php'; ?>

<?php
if (isset($_GET['id'])) {
    // Make sure the ID is an integer to prevent SQL injection
    $id = intval($_GET['id']);
    
    // Check if there's a valid connection
    if ($conn) {
        // Prepare and execute the query
        $qry = $conn->query("SELECT * FROM members WHERE id = $id");

        if ($qry->num_rows > 0) {
            // Fetch the data into an associative array
            $row = $qry->fetch_assoc();
            foreach ($row as $k => $v) {
                $$k = $v;
            }
        } else {
            echo "No member found with ID: $id";
        }
    } else {
        die("Connection failed: " . $conn->connect_error);
    }
}
?>

<div class="container-fluid">
    <form action="" id="manage-member">
        <div id="msg"></div>
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : ''; ?>" class="form-control">

        <div class="row form-group">
            <div class="col-md-4">
                <label class="control-label">ID No.</label>
                <input type="text" name="member_id" class="form-control" value="<?php echo isset($member_id) ? $member_id : ''; ?>" placeholder="Auto-generate ID if empty">
                <small><i>Leave this blank if you want to auto-generate the ID number.</i></small>
            </div>
        </div>

        <div class="row form-group">
            
            <div class="col-md-4">
                <label class="control-label">First Name</label>
                <input type="text" name="firstname" class="form-control" value="<?php echo isset($firstname) ? $firstname : ''; ?>" required>
            </div>
            <div class="col-md-4">
                <label class="control-label">Middle Name</label>
                <input type="text" name="middlename" class="form-control" value="<?php echo isset($middlename) ? $middlename : ''; ?>">
            </div>
            <div class="col-md-4">
                <label class="control-label">Last Name</label>
                <input type="text" name="lastname" class="form-control" value="<?php echo isset($lastname) ? $lastname : ''; ?>" required>
            </div>
        </div>

        <div class="row form-group">
            <div class="col-md-4">
                <label class="control-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo isset($email) ? $email : ''; ?>" required>
            </div>
            <div class="col-md-4">
                <label class="control-label">Contact #</label>
                <input type="text" name="contact" class="form-control" value="<?php echo isset($contact) ? $contact : ''; ?>" required>
            </div>
            <div class="col-md-4">
                <label class="control-label">Gender</label>
                <select name="gender" required class="custom-select">
                    <option value="Male" <?php echo (isset($gender) && $gender == 'Male') ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo (isset($gender) && $gender == 'Female') ? 'selected' : ''; ?>>Female</option>
                </select>
            </div>
        </div>

        <div class="row form-group">
            <div class="col-md-12">
                <label class="control-label">Address</label>
                <textarea name="address" class="form-control"><?php echo isset($address) ? $address : ''; ?></textarea>
            </div>
        </div>

        <!-- Plan, Package, Trainer Fields -->
        <div class="row form-group">
            <div class="col-md-4">
                <label class="control-label">Plan</label>
                <select name="plan_id" required class="custom-select select2">
                    <option value=""></option>
                    <?php
                    $qry = $conn->query("SELECT * FROM plans ORDER BY plan ASC");
                    while ($row = $qry->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>" <?php echo isset($plan_id) && $plan_id == $row['id'] ? 'selected' : ''; ?>><?php echo ucwords($row['plan']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="control-label">Package</label>
                <select name="package_id" required class="custom-select select2">
                    <option value=""></option>
                    <?php
                    $qry = $conn->query("SELECT * FROM packages ORDER BY package ASC");
                    while ($row = $qry->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>" <?php echo isset($package_id) && $package_id == $row['id'] ? 'selected' : ''; ?>><?php echo ucwords($row['package']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="control-label">Trainer</label>
                <select name="trainer_id" class="custom-select select2">
                    <option value=""></option>
                    <?php
                    $qry = $conn->query("SELECT * FROM trainers ORDER BY name ASC");
                    while ($row = $qry->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>" <?php echo isset($trainer_id) && $trainer_id == $row['id'] ? 'selected' : ''; ?>><?php echo ucwords($row['name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
    </form>
</div>

<!-- Include jQuery if it's not already in your project -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $('#manage-member').submit(function(e) {
        e.preventDefault();
        start_load();

        $.ajax({
            url: 'ajax.php?action=save_member',
            method: 'POST',
            data: $(this).serialize(),
            success: function(resp) {
                if (resp == "1") {
                    alert_toast("Data successfully saved.", 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else if (resp == "2") {
                    $('#msg').html('<div class="alert alert-danger">ID No already exists.</div>');
                    end_load();
                } else {
                    $('#msg').html('<div class="alert alert-danger">An error occurred.</div>');
                    end_load();
                }
            }
        });
    });
</script>
