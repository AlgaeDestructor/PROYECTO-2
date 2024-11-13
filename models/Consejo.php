<?php
class Consejo {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function getAllConsejos() {
        $sql = "SELECT * FROM consejos";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
    public function getConsejoById($id) {
        $sql = "SELECT * FROM consejos WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public function createConsejo($data) {
        $sql = "INSERT INTO consejos (titulo, descripcion_breve, texto_explicativo, etiquetas) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['titulo'],
            $data['descripcion_breve'],
            $data['texto_explicativo'],
            $data['etiquetas']
        ]);
    }
    public function updateConsejo($id, $data) {
        $sql = "UPDATE consejos SET titulo = ?, descripcion_breve = ?, texto_explicativo = ?, etiquetas = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['titulo'],
            $data['descripcion_breve'],
            $data['texto_explicativo'],
            $data['etiquetas'],
            $id
        ]);
    }
    public function deleteConsejo($id) {
        $sql = "DELETE FROM consejos WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>
