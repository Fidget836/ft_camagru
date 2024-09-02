<?php

class photoModel {
    private $db;
    private $stmt;
    private $imageData;

    public function __construct($db, $imageData) {
        $this->db = $db;
        $this->imageData = $imageData;
    }

    public function stockPhoto($id) {
        try {
            // Préparer la requête SQL pour insérer l'image sous forme de BLOB
            $userId = $id; // Remplacez par l'ID de l'utilisateur connecté

            $this->stmt = $this->db->conn->prepare("INSERT INTO posts (user_id, photoData) VALUES (?, ?)");
            $this->stmt->bindParam(1, $userId, PDO::PARAM_INT);
            $this->stmt->bindParam(2, $this->imageData, PDO::PARAM_LOB);

            if ($this->stmt->execute()) {
                // Stockage réussi, renvoyer l'image au client
                header('Content-Type: image/jpeg');
                echo $this->imageData; // Envoyer l'image au client
                http_response_code(200);
            } else {
                http_response_code(500);
                echo 'Erreur lors de l\'enregistrement de l\'image dans la base de données.';
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $e->getMessage()]);
        }
    }

    public function recoverPictures($id, $nbPicture) {
        $this->stmt = $this->db->conn->prepare("SELECT photoData FROM posts WHERE user_id = ? ORDER BY create_at DESC LIMIT ?");
        $this->stmt->bindParam(1, $id, PDO::PARAM_INT);
        $this->stmt->bindParam(2, $nbPicture, PDO::PARAM_INT);
        $this->stmt->execute();
        $pictures = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

        $picturesBase64 = [];
        foreach($pictures as $picture) {
            $picturesBase64[] = base64_encode($picture['photoData']);
        }

        echo json_encode(['status' => 'success', 'result' => $picturesBase64]);
    }
}

?>
