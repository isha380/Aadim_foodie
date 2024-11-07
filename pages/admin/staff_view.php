<?php
session_start();
include "../database/connection.php";

//checking if user logged in

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php'); // Redirect to login if not logged in
    exit();
}

//retrieve
$adminName = $_SESSION['admin'];

//fetching
 $employee =[];
 $query="SELECT * FROM staff_info ";
 $result = mysqli_query($conn,$query);
 if($result){
    while($row=mysqli_fetch_assoc($result)){
        $employee[]=$row;
    }
 }else{
    echo "Error ACCESS DENIED!! " .mysqli_error($conn);
 }

// Initialize search results
$searchResults = $employee; // Default to all students
$searchMessage = ""; // Message variable for search feedback

if (isset($_POST['submit'])) {
    $search = mysqli_real_escape_string($conn, $_POST['search_data']); // Secure the search input

    if (empty($search)) {
        $searchMessage = "Please enter a search term."; // Message for empty search input
    } else {
        $sql = "SELECT * FROM staff_info WHERE Staff_Id LIKE '%$search%' OR Name LIKE '%$search%'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $searchResults = []; // Clear previous results
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $searchResults[] = $row; // Store only search results
                }
            } else {
                $searchMessage = "No results found for '$search'."; // Message for no matching records
            }
        } else {
            echo "Search Error: " . mysqli_error($conn);
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/component.css">
    <link rel="stylesheet" href="../../assets/css/responsive.css">
    <link rel="stylesheet" href="../../assets/css/user_content.css">
</head>

<body class="dashboard">
    <div class="dash-wrapper">
        <div class="dash-nav-wrapper">
            <div class="dash-nav-img">
                <img src="../../assets/image/icon/final_light logo.png">
            </div>
            <div class="dash-nav-profile">
                <div class="dash-profile"><svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 24 24">
                        <path fill="currentColor" fill-rule="evenodd" d="M12 4a8 8 0 0 0-6.96 11.947A4.99 4.99 0 0 1 9 14h6a4.99 4.99 0 0 1 3.96 1.947A8 8 0 0 0 12 4m7.943 14.076q.188-.245.36-.502A9.96 9.96 0 0 0 22 12c0-5.523-4.477-10-10-10S2 6.477 2 12a9.96 9.96 0 0 0 2.057 6.076l-.005.018l.355.413A9.98 9.98 0 0 0 12 22q.324 0 .644-.02a9.95 9.95 0 0 0 5.031-1.745a10 10 0 0 0 1.918-1.728l.355-.413zM12 6a3 3 0 1 0 0 6a3 3 0 0 0 0-6" clip-rule="evenodd" />
                    </svg></div>
                <div class="dash-profile-name">
                    <h1>Welcome,</br> <?php echo htmlspecialchars($adminName); ?>!</h1>
                </div>

            </div>
            <div class="dash-panel">
                <div class="dash-panel-txt">
                    <span class="dash underline">Dashboard</span>
                </div>
                <div class="dash-panel-container">

                    <div class="dash-panel-wrapper status">
                        <div class="dash-status-txt">
                            <span class="dash dropdown ">Status</span>


                        </div>
                    </div>
                    <div class="dash-panel-wrapper users">
                        <div class="dash-users-txt">
                            <span class="dash dropdown" onclick="dropdown_show(this)">Users</span>
                            <div class="dropdown-content" style="display: none;">
                                <a href="../admin/user_content.php">Users</a>
                                <a href="../admin/user_add.php">Add User</a>
                            </div>
                        </div>
                    </div>
                    <div class="dash-panel-wrapper staffs">
                        <div class="dash-staffs-txt">
                            <span class="dash dropdown" onclick="dropdown_show(this)">Staffs</span>
                            <div class="dropdown-content" style="display: none;">
                                <a href="../admin/staff_view.php">Users</a>
                                <a href="../admin/staff_add.php">Add Staffs</a>
                            </div>
                        </div>
                    </div>
                    <div class="dash-panel-wrapper menu">
                        <div class="dash-menu-txt">
                            <span class="dash dropdown" onclick="dropdown_show(this)">Menu</span>
                            <div class="dropdown-content" style="display: none;">
                                <a href="../admin/menu_view.php">View </a>
                                <a href="../admin/menu_add.php">Add Menu</a>
                            </div>
                        </div>
                    </div>

                    <button  class="btn reg-btn dash"><a href="../admin/admin_dash.php"  style="text-decoration: none;">Back</a>
                    </button>

                </div>
            </div>
        </div>
        <div class="dash-view-wrapper">
            <div class="dash-view-txt">
                <h1>DASHBOARD</h1>
            </div>
            <!-- student info view     -->
            <button class="student-info-btn">REGISTERED STAFF DETAILS</button>
            <form method="POST" class="search-wrapper">
                <div class="search-box">
                    <input type="text" placeholder="Search id/name" name="search_data">
                </div>
                <div class="search-btn">
                    <button class="btn search" name="submit">Search</button>
                </div>
            </form>
            <?php if ($searchMessage): // Display the search message if it exists ?>
                <div class="search-message">
                    <p><?php echo htmlspecialchars($searchMessage); ?></p>
                </div>
            <?php endif; ?>
            <div class="table student">
                
                <table>
                    <thead>
                        <tr>
                            <th>Roll Num</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                     <?php  foreach($searchResults as $staff):    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($staff['Staff_Id']); ?> </td>
                            <td><?php echo htmlspecialchars($staff['Name']); ?></td>
                            <td><?php echo htmlspecialchars($staff['Role']); ?></td>
                            <td><?php echo htmlspecialchars($staff['Email']); ?></td>
                            <td><?php echo htmlspecialchars($staff['Phone']); ?></td>
                            <td>
                                    <div class="action button">
                                        <!-- Update Button -->
                                        <div class="update button">

                                            <a href="update_staff.php?id=<?php echo $staff['Id']; ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M12 21q-1.875 0-3.512-.712t-2.85-1.925t-1.925-2.85T3 12t.713-3.512t1.924-2.85t2.85-1.925T12 3q2.05 0 3.888.875T19 6.35V4h2v6h-6V8h2.75q-1.025-1.4-2.525-2.2T12 5Q9.075 5 7.038 7.038T5 12t2.038 4.963T12 19q2.625 0 4.588-1.7T18.9 13h2.05q-.375 3.425-2.937 5.713T12 21m2.8-4.8L11 12.4V7h2v4.6l3.2 3.2z" />
                                                </svg>
                                            </a>
                                        </div>

                                        <!-- Delete Button -->
                                        <div class="delete button">
                                            <a href="../admin/delete_staff.php?id=<?php echo $staff['Id']; ?>" onclick="return confirm('Are you sure you want to delete this?')">

                                                <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M7.616 20q-.691 0-1.153-.462T6 18.384V6H5V5h4v-.77h6V5h4v1h-1v12.385q0 .69-.462 1.153T16.384 20zm2.192-3h1V8h-1zm3.384 0h1V8h-1z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>


        </div>

    </div>

    <script src="../js/dropdown-dash.js">
        
        </script>




</body>

</html>