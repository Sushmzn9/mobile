<?php
$absPath = '../';
include $absPath . "classes/database.php";

$db = new Database();
$db->query("SHOW TABLES");
$result = $db->getResult();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tableName = $_POST['tableName'];
    $className = $_POST['className'];

    // Get primary key
    $db->query("SHOW KEYS FROM $tableName WHERE Key_name = 'PRIMARY'");
    $primaryKeyResult = $db->getResult();
    $idField = $primaryKeyResult[0]['Column_name'] ?? 'id';

    // Get columns
    $db->query("DESCRIBE $tableName");
    $columnsResult = $db->getResult();

    // Build class content
    $classContent = "<?php\n\n";
    $classContent .= "class $className {\n\n";

    // Private fields
    foreach ($columnsResult as $column) {
        $colName = $column['Field'];
        $classContent .= "    private \$$colName;\n";
    }

    $classContent .= "\n    private \$conn;\n\n";

    // Constructor
    $classContent .= "    public function __construct(\$conn) {\n";
    $classContent .= "        \$this->conn = \$conn;\n";
    $classContent .= "    }\n\n";

    // Getter methods
    foreach ($columnsResult as $column) {
        $colName = $column['Field'];
        $methodName = 'get' . ucfirst($colName);

        $classContent .= "    public function $methodName() {\n";
        $classContent .= "        return \$this->$colName;\n";
        $classContent .= "    }\n\n";
    }

    // selectById()
    $classContent .= "    public function selectById(\$id) {\n";
    $classContent .= "        \$sql = \"SELECT * FROM $tableName WHERE $idField = ?\";\n";
    $classContent .= "        \$stmt = \$this->conn->prepare(\$sql);\n";
    $classContent .= "        \$stmt->bind_param(\"s\", \$id);\n";
    $classContent .= "        \$stmt->execute();\n";
    $classContent .= "        return \$stmt->get_result()->fetch_assoc();\n";
    $classContent .= "    }\n\n";

    // selectAll()
    $classContent .= "    public function selectAll() {\n";
    $classContent .= "        \$result = \$this->conn->query(\"SELECT * FROM $tableName\");\n";
    $classContent .= "        return \$result->fetch_all(MYSQLI_ASSOC);\n";
    $classContent .= "    }\n\n";

    // insert()
    $fields = [];
    $placeholders = [];
    $bindString = '';
    foreach ($columnsResult as $column) {
        if ($column['Extra'] !== 'auto_increment') {
            $fields[] = $column['Field'];
            $placeholders[] = '?';
            $bindString .= 's';
        }
    }
    $fieldList = implode(', ', $fields);
    $placeholderList = implode(', ', $placeholders);

    $classContent .= "    public function insert(" . '$' . "data) {\n";
    $classContent .= "        \$sql = \"INSERT INTO $tableName ($fieldList) VALUES ($placeholderList)\";\n";
    $classContent .= "        \$stmt = \$this->conn->prepare(\$sql);\n";
    $bindParams = implode(', ', array_map(fn($f) => "\$data['$f']", $fields));
    $classContent .= "        \$stmt->bind_param(\"$bindString\", $bindParams);\n";
    $classContent .= "        return \$stmt->execute();\n";
    $classContent .= "    }\n\n";

    // update()
    $updateFields = [];
    foreach ($fields as $field) {
        $updateFields[] = "$field = ?";
    }
    $updateList = implode(', ', $updateFields);
    $classContent .= "    public function update(\$id, \$data) {\n";
    $classContent .= "        \$sql = \"UPDATE $tableName SET $updateList WHERE $idField = ?\";\n";
    $classContent .= "        \$stmt = \$this->conn->prepare(\$sql);\n";
    $classContent .= "        \$stmt->bind_param(\"$bindString" . "s\", $bindParams, \$id);\n";
    $classContent .= "        return \$stmt->execute();\n";
    $classContent .= "    }\n";

    $classContent .= "}\n";


    $filename = $absPath . "classes/$className.php";

    if (file_exists($filename)) {
        echo "<p style='color:red;'>Class <strong>$className</strong> already exists for table <strong>$tableName</strong>. No file was created.</p>";
    } else {
        if (file_put_contents($filename, $classContent)) {
            echo "<p>Class <strong>$className</strong> created with select, insert, and update methods at <code>$filename</code></p>";
        } else {
            echo "<p style='color:red;'>Failed to create class file!</p>";
        }
    }
}
?>

<form method="POST">
    <select name="tableName" required>
        <option value="">Select Table</option>
        <?php foreach ($result as $table) {
            $tableName = $table['Tables_in_mobile'];
            echo "<option value=\"$tableName\">$tableName</option>";
        } ?>
    </select>
    <br />
    <input type="text" name="className" placeholder="Class Name" required> <br />
    <input type="submit" value="Generate Class">
</form>