<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitBook - Reserve Activity</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <?php
    include('../config/db.php');

    $message = '';

    // Fetch activities
    $activities = $conn->query("SELECT ActiviteID, Nom FROM activites")->fetch_all(MYSQLI_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $activityID = $_POST['activity'];
        $reservationDate = $_POST['reservation_date'];

        $conn->query("INSERT INTO membres (Prenom, Nom, Email, Telephone) VALUES ('$firstname', '$lastname', '$email', '$phone')");
        $memberID = $conn->insert_id;
        if ($conn->query("INSERT INTO reservations (MembreID, ActiviteID, ReservationDate) VALUES ('$memberID', '$activityID', '$reservationDate')")) {
            $message = "Reservation successfully created.";
        } else {
            $message = "An error occurred.";
        }
    }
    ?>

    <main class="w-full px-4">
        <?php if ($message): ?>
            <div class="text-center mb-4 text-green-600 font-medium">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="max-w-lg mx-auto mt-4 p-6 bg-white rounded-lg shadow-md space-y-6">
            <h1 class="text-2xl font-bold text-center text-gray-800">Reserve an Activity</h1>
            <input type="text" name="firstname" placeholder="First Name" required 
                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            <input type="text" name="lastname" placeholder="Last Name" required 
                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            <input type="email" name="email" placeholder="Email" required 
                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            <input type="text" name="phone" placeholder="Phone Number" required 
                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            <select name="activity" required 
                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <option value="" disabled selected>Select an Activity</option>
                <?php foreach ($activities as $activity): ?>
                    <option value="<?= $activity['ActiviteID'] ?>"><?= $activity['Nom'] ?></option>
                <?php endforeach; ?>
            </select>
            <input type="datetime-local" name="reservation_date" required 
                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            <button type="submit" 
                class="w-full py-3 bg-gray-800 text-white rounded-lg font-semibold hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-500">
                Reserve Now
            </button>
        </form>
        <div class="max-w-xs mx-auto mt-4">
            <a href="display.php" 
                class="w-full block text-center py-3 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                View Reservations
            </a>
        </div>
    </main>
</body>
</html>
