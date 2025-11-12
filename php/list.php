<?php
require 'db.php';

$result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Submitted Messages</title>
<style>
body { background: #2c3e50; color: #fff; font-family: Arial; padding: 40px; }
table { border-collapse: collapse; width: 100%; background: #34495e; border-radius: 10px; overflow: hidden; }
th, td { border: 1px solid #555; padding: 10px; text-align: left; }
th { background: #0056b3; }
tr:nth-child(even) { background: #3b4a5a; }
</style>
</head>
<body>
<h2>Submitted Messages</h2>
<table>
<tr>
  <th>ID</th>
  <th>Name</th>
  <th>Email</th>
  <th>Phone No</th>
  <th>Company Name</th>
  <th>Message</th>
  <th>Date</th>
</tr>
<?php while($row = $result->fetch_assoc()) { ?>
<tr>
  <td><?= $row['id'] ?></td>
  <td><?= htmlspecialchars($row['name']) ?></td>
  <td><?= htmlspecialchars($row['email']) ?></td>
  <td><?= htmlspecialchars($row['phoneNo']) ?></td>
  <td><?= htmlspecialchars($row['company']) ?></td>
  <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
  <td><?= $row['created_at'] ?></td>
</tr>
<?php } ?>
</table>
</body>
</html>
