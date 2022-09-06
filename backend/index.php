<?php

$person = new Person();
$person->create();
$person->insert('Jose', 'Lopez', 'jose@example.com');
$person->insert('Juan', 'Gomez', 'juan@example.com');
$person->show();
$person->close();


class Person {

    private $conn;

    public function __construct()
    {
        $servername = "172.10.2.12";
        $username = "admin";
        $password = "c34sm_wdDZ1";
        $dbname = "smw_api";

        $this->conn = new mysqli($servername, $username, $password, $dbname);
        
        if ($this->conn->connect_error) {
            die("La conexión a fallado: " . $this->conn->connect_error . "<br>");
        }

    }

    public function create() {
        $sql = "DROP TABLE IF EXISTS persons";

        if ($this->conn->query($sql) === TRUE) {
            echo "Tabla eliminada exitosamente." . "<br>";
        }
        
        $sql = "CREATE TABLE IF NOT EXISTS persons (
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            first_name VARCHAR(30) NOT NULL,
            last_name VARCHAR(30) NOT NULL,
            email VARCHAR(70) NOT NULL
        )";

        if ($this->conn->query($sql) === TRUE) {
            echo "Tabla creada exitosamente." . "<br>";
        } else{
            echo "Error: " . $sql . "<br>" . $this->conn->error . "<br>";
        }

    }

    public function insert($first_name, $last_name, $email) 
    {
        $sql = "INSERT INTO persons (id, first_name, last_name, email) 
        VALUES (null,'".$first_name."','".$last_name."','".$email."')";        
        if ($this->conn->query($sql) === TRUE) {
            echo "Registro creado exitosamente" . "<br>";
          } else {
            echo "Error: " . $sql . "<br>" . $this->conn->error . "<br>";
          }
          
    }

    public function show() 
    {
        $sql = "SELECT * FROM persons";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "id: " . $row["id"]. " - Apellido y nombres: " . $row["first_name"]. " " . $row["last_name"] . " - Email: " . $row["last_name"] . "<br>";
        }
        } else {
            echo "Sin datos";
        }       
    }

    public function close()
    {
        $this->conn->close();
    }
}
