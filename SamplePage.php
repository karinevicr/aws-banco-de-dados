<?php include "../inc/dbinfo.inc"; ?>
<html>
<head>
  <title>Employee Management</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 20px;
    }
    h1 {
      color: #333;
      text-align: center;
    }
    form {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      max-width: 600px;
      margin: 0 auto;
    }
    table.form-table {
      width: 100%;
      border-collapse: collapse;
    }
    table.form-table td {
      padding: 10px;
    }
    table.form-table input[type="text"],
    table.form-table input[type="number"],
    table.form-table input[type="date"] {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }
    table.form-table input[type="submit"] {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 4px;
      cursor: pointer;
    }
    table.form-table input[type="submit"]:hover {
      background-color: #218838;
    }
    table.data-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    table.data-table th,
    table.data-table td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
    table.data-table th {
      background-color: #007bff;
      color: white;
    }
    table.data-table tr:hover {
      background-color: #f1f1f1;
    }
    .error {
      color: red;
      text-align: center;
      margin-top: 20px;
    }
  </style>
</head>
<body>
<h1>Employee Management</h1>

<?php

  /* Connect to MySQL and select the database. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) {
    echo "<p class='error'>Failed to connect to MySQL: " . mysqli_connect_error() . "</p>";
    exit();
  }

  $database = mysqli_select_db($connection, DB_DATABASE);

  if (!$database) {
    echo "<p class='error'>Failed to select database: " . mysqli_error($connection) . "</p>";
    exit();
  }

  /* Ensure that the EMPLOYEES table exists. */
  VerifyEmployeesTable($connection, DB_DATABASE);

  /* If input fields are populated, add a row to the EMPLOYEES table. */
  $employee_name = htmlentities($_POST['NAME'] ?? '');
  $employee_address = htmlentities($_POST['ADDRESS'] ?? '');
  $employee_age = intval($_POST['IDADE'] ?? 0); // Idade como integer
  $employee_favorite_color = htmlentities($_POST['COR_FAVORITA'] ?? ''); // Cor favorita como string
  $employee_height = floatval($_POST['ALTURA'] ?? 0.0); // Altura como numeric
  $employee_birthdate = htmlentities($_POST['DATA_ANIVERSARIO'] ?? ''); // Data de aniversário como date

  if (!empty($employee_name) || !empty($employee_address) || !empty($employee_age) || !empty($employee_favorite_color) || !empty($employee_height) || !empty($employee_birthdate)) {
    AddEmployee($connection, $employee_name, $employee_address, $employee_age, $employee_favorite_color, $employee_height, $employee_birthdate);
  }
?>

<!-- Input form -->
<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="POST">
  <table class="form-table">
    <tr>
      <td>NAME</td>
      <td>ADDRESS</td>
      <td>IDADE</td>
      <td>COR FAVORITA</td>
      <td>ALTURA</td>
      <td>DATA DE ANIVERSÁRIO</td>
    </tr>
    <tr>
      <td>
        <input type="text" name="NAME" maxlength="45" size="30" />
      </td>
      <td>
        <input type="text" name="ADDRESS" maxlength="90" size="60" />
      </td>
      <td>
        <input type="number" name="IDADE" maxlength="3" size="5" />
      </td>
      <td>
        <input type="text" name="COR_FAVORITA" maxlength="50" size="20" />
      </td>
      <td>
        <input type="number" step="0.01" name="ALTURA" />
      </td>
      <td>
        <input type="date" name="DATA_ANIVERSARIO" />
      </td>
      <td>
        <input type="submit" value="Add Data" />
      </td>
    </tr>
  </table>
</form>

<!-- Display table data. -->
<table class="data-table">
  <tr>
    <th>ID</th>
    <th>NAME</th>
    <th>ADDRESS</th>
    <th>IDADE</th>
    <th>COR FAVORITA</th>
    <th>ALTURA</th>
    <th>DATA DE ANIVERSÁRIO</th>
  </tr>

<?php

$result = mysqli_query($connection, "SELECT * FROM EMPLOYEES");

if ($result) {
  while($query_data = mysqli_fetch_row($result)) {
    echo "<tr>";
    echo "<td>", $query_data[0], "</td>",
         "<td>", $query_data[1], "</td>",
         "<td>", $query_data[2], "</td>",
         "<td>", $query_data[3], "</td>",
         "<td>", $query_data[4], "</td>",
         "<td>", $query_data[5], "</td>",
         "<td>", $query_data[6], "</td>";
    echo "</tr>";
  }
} else {
  echo "<tr><td colspan='7' class='error'>Error fetching data: " . mysqli_error($connection) . "</td></tr>";
}
?>

</table>

<!-- Clean up. -->
<?php

  if (isset($result)) {
    mysqli_free_result($result);
  }
  mysqli_close($connection);

?>

</body>
</html>


<?php

/* Add an employee to the table. */
function AddEmployee($connection, $name, $address, $age, $favorite_color, $height, $birthdate) {
   $n = mysqli_real_escape_string($connection, $name);
   $a = mysqli_real_escape_string($connection, $address);
   $ag = intval($age); // Garantir que a idade seja um inteiro
   $fc = mysqli_real_escape_string($connection, $favorite_color);
   $h = floatval($height); // Garantir que a altura seja numérica
   $bd = mysqli_real_escape_string($connection, $birthdate);

   $query = "INSERT INTO EMPLOYEES (NAME, ADDRESS, IDADE, COR_FAVORITA, ALTURA, DATA_ANIVERSARIO) VALUES ('$n', '$a', $ag, '$fc', $h, '$bd');";

   if(!mysqli_query($connection, $query)) {
     echo "<p class='error'>Error adding employee data: " . mysqli_error($connection) . "</p>";
   }
}

/* Check whether the table exists and, if not, create it. */
function VerifyEmployeesTable($connection, $dbName) {
  if(!TableExists("EMPLOYEES", $connection, $dbName))
  {
     $query = "CREATE TABLE EMPLOYEES (
         ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
         NAME VARCHAR(45),
         ADDRESS VARCHAR(90),
         IDADE INT,
         COR_FAVORITA VARCHAR(50),
         ALTURA DECIMAL(5,2),
         DATA_ANIVERSARIO DATE
       )";

     if(!mysqli_query($connection, $query)) {
       echo "<p class='error'>Error creating table: " . mysqli_error($connection) . "</p>";
     }
  }
}

/* Check for the existence of a table. */
function TableExists($tableName, $connection, $dbName) {
  $t = mysqli_real_escape_string($connection, $tableName);
  $d = mysqli_real_escape_string($connection, $dbName);

  $checktable = mysqli_query($connection,
      "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

  if($checktable && mysqli_num_rows($checktable) > 0) return true;

  return false;
}
?>