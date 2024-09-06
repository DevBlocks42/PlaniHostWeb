<?php 

class SettingAccess extends Access 
{
    public function getSettingById($id)
    {
        $sql = "SELECT * FROM settings WHERE id = ?;";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc())
        {
            $setting = new Setting($row['id'], $row['clef'], $row['valeur']);
            return $setting;
        }
        return null;
    }
    public function getSettingByClef($clef)
    {
        $sql = "SELECT * FROM settings WHERE clef = ?;";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("s", $clef);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc())
        {
            $setting = new Setting($row['id'], $row['clef'], $row['valeur']);
            return $setting;
        }
        return null;
    }
    public function getSettings($limit, $offset)
    {
        $sql = "SELECT * FROM settings LIMIT ? OFFSET ?;";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        $settings = [];
        while($row = $result->fetch_assoc())
        {
            $setting = new Setting($row['id'], $row['clef'], $row['valeur']);
            array_push($settings, $setting);
        }
        return $settings;
    }
    public function addSetting($setting)
    {
        $sql = "INSERT INTO settings (clef, valeur) VALUES(?, ?);";
        $stmt = $this->database->prepare($sql);
        $clef = $setting->getClef();
        $val = $setting->getValeur();
        $stmt->bind_param("ss", $clef, $val);
        $stmt->execute();
    }
    public function updateSetting($setting)
    {
        $sql = "UPDATE settings SET clef=?, valeur=? WHERE id=?";
        $stmt = $this->database->prepare($sql);
        $id = $setting->getId();
        $clef = $setting->getClef();
        $val = $setting->getValeur();
        $stmt->bind_param("ssi", $clef, $val, $id);
        $stmt->execute();
    }
}