<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {

    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "planilhaweb";
    $conn = new mysqli($host, $user, $password, $database, 666);
    
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    $fileName = $_FILES['image']['name'];
    $fileType = $_FILES['image']['type'];
    $fileData = file_get_contents($_FILES['image']['tmp_name']);
    $fileSize = $_FILES['image']['size'];

    $sql = "INSERT INTO imagens (nome, tipo, dados, tamanho) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    $null = NULL; 
    $stmt->bind_param("ssbi", $fileName, $fileType, $null, $fileSize);
    $stmt->send_long_data(2, $fileData); // dados binários
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header('Location: nome_do_seu_arquivo_html.php?uploadSuccess');
        echo "Arquivo enviado e salvo no banco de dados com sucesso.";
    } else {
        echo "Erro ao salvar o arquivo no banco de dados.";
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo "Nenhum arquivo enviado ou erro no upload.";
}
?>