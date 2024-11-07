<?php
session_start();
include "../database/connection.php"; // Added semicolon
?>

<div class="data-container">
    <table class="table">
        <?php
        if (isset($_POST['submit'])) {
            $search = $_POST['search_data'];
            $sql = "SELECT * FROM student_info WHERE Rollnum LIKE '%$search%' OR Name LIKE '%$search%'"; // Added quotes around %$search%
            $result = mysqli_query($conn, $sql);
            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    echo 
                    '<thead>
                         <tr>
                            <th>Roll Num</th>
                            <th>Name</th>
                            <th>Faculty</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                         </tr>
                    </thead>'; 
              
                    while ($row = mysqli_fetch_assoc($result)) { // Method to fetch data from DB
                        echo '
                        <tbody>
                        <tr>
                            <td>' . htmlspecialchars($row['Rollnum']) . '</td>
                            <td>' . htmlspecialchars($row['Name']) . '</td>
                            <td>' . htmlspecialchars($row['Faculty']) . '</td>
                            <td>' . htmlspecialchars($row['Email']) . '</td>
                            <td>' . htmlspecialchars($row['Phone']) . '</td>
                        </tr>
                        </tbody>';
                    }
                } else {
                    echo '<h2>DATA NOT FOUND</h2>';
                }
            }
        }
        ?>
    </table>
</div>
