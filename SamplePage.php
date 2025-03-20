<?php include "../inc/dbinfo.inc"; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gerenciamento de Funcionários</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f9;
      color: #333;
      margin: 0;
      padding: 20px;
    }
    h1 {
      color: #4CAF50;
      text-align: center;
    }
    form {
      background-color: #ffffff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      max-width: 600px;
      margin: 20px auto;
    }
    .form-group {
      margin-bottom: 15px;
    }
    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
      color: #555;
    }
    .form-group input[type="text"], 
    .form-group input[type="date"] {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .form-row {
      display: flex;
      gap: 10px;
    }
    .form-row .form-group {
      flex: 1;
    }
    input[type="submit"] {
      background-color: #4CAF50;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      width: 100%;
      font-size: 16px;
    }
    input[type="submit"]:hover {
      background-color: #45a049;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
    }
    table, th, td {
      border: 1px solid #ddd;
    }
    th, td {
      padding: 12px;
      text-align: left;
    }
    th {
      background-color: #4CAF50;
      color: white;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    tr:hover {
      background-color: #f1f1f1;
    }
    .container {
      max-width: 1200px;
      margin: 0 auto;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Gerenciamento de Funcionários</h1>

    <!-- Input form -->
    <form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
      <!-- Primeira linha: Nome, Endereço, Comida Favorita -->
      <div class="form-row">
        <div class="form-group">
          <label for="NAME">Nome</label>
          <input type="text" id="NAME" name="NAME" maxlength="45" placeholder="Nome" required />
        </div>
        <div class="form-group">
          <label for="ADDRESS">Endereço</label>
          <input type="text" id="ADDRESS" name="ADDRESS" maxlength="90" placeholder="Endereço" required />
        </div>
        <div class="form-group">
          <label for="comida_favorita">Comida Favorita</label>
          <input type="text" id="comida_favorita" name="comida_favorita" maxlength="100" placeholder="Comida Favorita" />
        </div>
      </div>

      <!-- Segunda linha: Bebida Favorita, Cor Favorita, Data de Aniversário -->
      <div class="form-row">
        <div class="form-group">
          <label for="bebida_favorita">Bebida Favorita</label>
          <input type="text" id="bebida_favorita" name="bebida_favorita" maxlength="100" placeholder="Bebida Favorita" />
        </div>
        <div class="form-group">
          <label for="cor_favorita">Cor Favorita</label>
          <input type="text" id="cor_favorita" name="cor_favorita" maxlength="50" placeholder="Cor Favorita" />
        </div>
        <div class="form-group">
          <label for="data_aniversario">Data de Aniversário</label>
          <input type="date" id="data_aniversario" name="data_aniversario" />
        </div>
      </div>

      <!-- Botão de envio -->
      <input type="submit" value="Adicionar Funcionário" />
    </form>

    <?php
      /* Connect to MySQL and select the database. */
      $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

      if (mysqli_connect_errno()) echo "Falha ao conectar ao MySQL: " . mysqli_connect_error();

      $database = mysqli_select_db($connection, DB_DATABASE);

      /* Ensure that the EMPLOYEES table exists. */
      VerifyEmployeesTable($connection, DB_DATABASE);

      /* If input fields are populated, add a row to the EMPLOYEES table. */
      $employee_name = htmlentities($_POST['NAME']);
      $employee_address = htmlentities($_POST['ADDRESS']);
      $comida_favorita = htmlentities($_POST['comida_favorita']);
      $bebida_favorita = htmlentities($_POST['bebida_favorita']);
      $cor_favorita = htmlentities($_POST['cor_favorita']);
      $data_aniversario = htmlentities($_POST['data_aniversario']);

      if (strlen($employee_name) || strlen($employee_address) || strlen($comida_favorita) || strlen($bebida_favorita) || strlen($cor_favorita) || strlen($data_aniversario)) {
        AddEmployee($connection, $employee_name, $employee_address, $comida_favorita, $bebida_favorita, $cor_favorita, $data_aniversario);
      }
    ?>

    <!-- Display table data. -->
    <table>
      <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Endereço</th>
        <th>Comida Favorita</th>
        <th>Bebida Favorita</th>
        <th>Cor Favorita</th>
        <th>Data de Aniversário</th>
      </tr>

      <?php
      $result = mysqli_query($connection, "SELECT * FROM EMPLOYEES");

      while($query_data = mysqli_fetch_row($result)) {
        echo "<tr>";
        echo "<td>",$query_data[0], "</td>",
             "<td>",$query_data[1], "</td>",
             "<td>",$query_data[2], "</td>",
             "<td>",$query_data[3], "</td>",
             "<td>",$query_data[4], "</td>",
             "<td>",$query_data[5], "</td>",
             "<td>",$query_data[6], "</td>";
        echo "</tr>";
      }
      ?>
    </table>

    <!-- Clean up. -->
    <?php
      mysqli_free_result($result);
      mysqli_close($connection);
    ?>
  </div>
</body>
</html>

<?php

/* Add an employee to the table. */
function AddEmployee($connection, $name, $address, $comida_favorita, $bebida_favorita, $cor_favorita, $data_aniversario) {
   $n = mysqli_real_escape_string($connection, $name);
   $a = mysqli_real_escape_string($connection, $address);
   $cf = mysqli_real_escape_string($connection, $comida_favorita);
   $bf = mysqli_real_escape_string($connection, $bebida_favorita);
   $cor = mysqli_real_escape_string($connection, $cor_favorita);
   $da = mysqli_real_escape_string($connection, $data_aniversario);

   $query = "INSERT INTO EMPLOYEES (NAME, ADDRESS, comida_favorita, bebida_favorita, cor_favorita, data_aniversario) 
             VALUES ('$n', '$a', '$cf', '$bf', '$cor', '$da');";

   if(!mysqli_query($connection, $query)) echo("<p>Erro ao adicionar dados do funcionário.</p>");
}

/* Check whether the table exists and, if not, create it. */
function VerifyEmployeesTable($connection, $dbName) {
  if(!TableExists("EMPLOYEES", $connection, $dbName))
  {
     $query = "CREATE TABLE EMPLOYEES (
         ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
         NAME VARCHAR(45),
         ADDRESS VARCHAR(90),
         comida_favorita VARCHAR(100),
         bebida_favorita VARCHAR(100),
         cor_favorita VARCHAR(50),
         data_aniversario DATE
       )";

     if(!mysqli_query($connection, $query)) echo("<p>Erro ao criar a tabela.</p>");
  }
}

/* Check for the existence of a table. */
function TableExists($tableName, $connection, $dbName) {
  $t = mysqli_real_escape_string($connection, $tableName);
  $d = mysqli_real_escape_string($connection, $dbName);

  $checktable = mysqli_query($connection,
      "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

  if(mysqli_num_rows($checktable) > 0) return true;

  return false;
}
?>