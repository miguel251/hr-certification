<?php
require_once 'conexion.php';

$personas = [
    'Carlos Alberto Cisneros Montes',
    'Andrea Estefania Mendoza Villaseñor',
    'MARIBEL CALDERON QUIROZ',
    'Elizabeth Hernandez Villalobos',
    'Yesenia Gómez Fregoso',
    'Raquel Jaqueline Chitica Esquivel',
    'Faviola Ortiz Diaz',
    'Francisco Javier Salamanca Hernandez',
    'Gabriela Elizabeth Garcia Bobadilla',
    'Diego Alberto Murillo Arias',
    'Veronica Sereida Gonzalez Colio',
    'Maya Vanessa Sansores Pech',
    'Isabel Castañeda Cardenas',
    'Sandra Lorena Llamas Gonzalez',
    'Fernando Estrada Gónzalez',
    'David Isaac Cárdenas Cruz',
    'Dorian Ramón Rodríguez Aguirre',
    'Roberto Castañeda Piña',
    'ROMAN DEL ANGEL CHABLE',
    'Cesar Rafael Martínez Bernal',
    'Luis Miguel Aceves Aceves',
    'Erick Ernesto Garcia Jaso',
    'Carlos Alberto Resendiz Torres',
    'Isabel Miranda Mancilla',
    'Martha Maria De Alba Marin',
    'María del Rocío Pérez Pérez',
    'Jorge Alberto Osorio García',
    'Héctor Francisco Javier Rios Mariscal',
    'Anneliese Vianey Hernandez Guerrero',
    'Emmanuel Sinoe Casanova Mugarte',
    'Edgar Martin Rojas Morales',
    'Jesus Antonio Lagunes Camacho',
    'Julio Cesar Medina Pech',
    'Juan Manuel Ornelas Martínez',
    'Jose Manuel Hernandez Reyes',
    'Victor Hugo Muñoz Avalos',
    'José Miguel Navarro Mariscal',
    'CLAUDIA MARIBEL MANJARREZ LOZANO',
    'Juan Manuel Tamayo Siordia',
    'FERNANDO NEVAREZ CORTEZ',
    'Angel Gabriel De La Paz Cazares',
    'Oscar del Castillo Sandoval',
    'Carlos Abraham Pren Rodriguez',
    'José de Jesús Hernandez Ojeda',
    'José de Jesús Hernandez Ojeda',
    'JOSE ALFONSO PECH POOT',
    'Gabriel Edilberto Couoh Canul',
    'Eduardo De Jesus Velazquez Jimenez',
    'Humberto Fino Salas',
    'Antonio Palos Gutiérrez',
    'Emilia Ortiz Rosales',
    'Brandon Emanuel Vega Santana'
    ];

$promedio = [
    59.54,
    72.16,
    80.3,
    89.8,
    64.31,
    92.7,
    64.7,
    93.8,
    85.2,
    71.41,
    91,
    88.06,
    46.22,
    57.7,
    75.29,
    78.8,
    67.42,
    54.14,
    80.47,
    70.1,
    79.81,
    69.11,
    92.3,
    80.34,
    62.89,
    89.8,
    89.8,
    89,
    77.6,
    60,
    78.82,
    81.14,
    78.5,
    51.5,
    78.42,
    90,
    85.04,
    82.91,
    38.44,
    60.5,
    47.02,
    66.74,
    42,
    83.56,
    88.06,
    75.53,
    86.28,
    50.9,
    60.6,
    92.2,
    100,
    95
];
$idpersona =[];
$con = new Conexion();
$conn = $con->conexion();
for ($i=0; $i < count($personas) ; $i++) { 
    $sql = "SELECT id_empleado, CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) nombre FROM empleado
    WHERE CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno)
    COLLATE SQL_Latin1_General_Cp1_CI_AI LIKE :nombre";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":nombre", $personas[$i]);
    $stmt->execute();
    $bloques = $stmt->fetch();
    array_push($idpersona, $bloques["id_empleado"]);
    //array_push($idpersona, $bloques["nombre"]);
}

for ($i=0; $i < count($personas) ; $i++) { 
    insertar($promedio[$i], $idpersona[$i]);
    //echo $idpersona[$i] . ' ' .$personas[$i] .'<br/>';
}
echo 'true';

function insertar($promedio, $id)
{
    $con = new Conexion();
    $conn = $con->conexion();
    $sql = "INSERT INTO resultado_hr_termometro
    (id_empleado, quarter, anio, resultado_hr, fecha_creo, usuario_creo)
    VALUES(:id,1,2019,:promedio,GETDATE(), 2)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":promedio", $promedio);
    $stmt->execute();
}