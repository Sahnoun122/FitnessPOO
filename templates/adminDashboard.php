<?php
require_once '../models/classes.php';
require_once '../config/db.php';

session_start();

// Redirect if user is not an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit;
}

$admin_id = $_SESSION['user_id'];

$db = new DbConnection();
$pdo = $db->getConnection();

$user_sql = "SELECT * FROM Users WHERE UserID = :admin_id";
$stmt = $pdo->prepare($user_sql);
$stmt->execute([':admin_id' => $admin_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'], $_POST['action'])) {
    $reservation_id = $_POST['reservation_id'];
    $action = $_POST['action'];
    $status = ($action === 'accept') ? 'accepted' : 'rejected';

    $update_sql = "UPDATE Reservations SET status = :status WHERE ResID = :reservation_id";
    $stmt_update = $pdo->prepare($update_sql);
    $stmt_update->execute([
        ':status' => $status,
        ':reservation_id' => $reservation_id
    ]);

    $_SESSION['message'] = "Reservation has been $status.";
    header("Location: admin_dashboard.php");
    exit;
}

$reservations_sql = "SELECT r.ResID, r.ResDate, r.status, u.Name AS MemberName, a.Name AS ActivityName 
                    FROM Reservations r
                    JOIN Members u ON r.MemberID = u.MemberID
                    JOIN Activities a ON r.ActivityID = a.ActivityID";
$stmt_reservations = $pdo->query($reservations_sql);
$reservations = $stmt_reservations->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="icon" href="../assets/media/court.png"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</head>
<body class="bg-gray-100">

<!-- Main -->
<button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
   <span class="sr-only">Open sidebar</span>
   <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
   <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
   </svg>
</button>

<aside id="default-sidebar" class="fixed top-0 left-0 z-40 w-80 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full overflow-y-auto bg-gray-50 dark:bg-gray-800">
    <!-- Sidebar Menu -->
    <div class="flex flex-col">
        <img src="https://images.rawpixel.com/image_social_landscape/cHJpdmF0ZS9sci9pbWFnZXMvd2Vic2l0ZS8yMDI0LTA4L2thdGV2NjQ0N19waG90b19vZl93b29kZW5fZ2F2ZWxfaW5fdGhlX2NvdXJ0X2dhdmVsX3BsYWNlX29uX3RoZV83MmVhZDZjNS1lNGIxLTRlZDctYWIzNC03NThiMDVmZmY3YjRfMS5qcGc.jpg" alt="Lawyer Photo" class="object-cover">
        <div class="px-3 py-4">
            <h2 class="text-3xl font-semibold text-center text-white mb-6">LawyerUp</h2>
            <hr class="h-1 bg-gray-500 border-0 rounded dark:bg-gray-400">
        </div>
    </div>

      <ul class="space-y-2 font-medium px-3 pb-4">
        <li>
            <?php if ($user): ?>
                <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <img class="flex-shrink-0 w-8 h-8" src="">
                    <span class="flex-1 ms-3 whitespace-nowrap"><?php echo $user['Username']; ?> &#128994;</span>
                </a>
            <?php else: ?>
                <p class="text-base text-red-500">User not found.</p>
            <?php endif; ?>
        </li>
        <li>
            <a href="adminDashboard.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                  <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z"/>
               </svg>
               <span class="ms-3">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="logout.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 16">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Log Out</span>
            </a>
        </li>
      </ul>
   </div>
</aside>

<!-- Main -->
<div class="p-8 sm:ml-80">

    <h2 class="text-2xl font-semibold text-gray-700 mb-6">Reservations</h2>
    <div class="flex items-center justify-center overflow-x-auto">
        <table class="min-w-full table-auto border-collapse bg-white shadow-lg">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-white">Member</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-white">Activity</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-white">Reservation Date</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-white">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $reservation): ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm"><?php echo htmlspecialchars($reservation['MemberName'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="px-6 py-4 text-sm"><?php echo htmlspecialchars($reservation['ActivityName'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="px-6 py-4 text-sm"><?php echo htmlspecialchars($reservation['ResDate'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="px-6 py-4">
                            <form method="POST" action="" class="flex space-x-2">
                                <input type="hidden" name="reservation_id" value="<?php echo $reservation['ResID']; ?>">
                                <button name="action" value="accept" class="text-xl hover:scale-105">✅</button>
                                <button name="action" value="reject" class="text-xl hover:scale-105">❌</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>