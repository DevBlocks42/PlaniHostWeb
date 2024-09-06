<?php 

class SettingController extends Controller
{
    public function getSettingById($id)
    {
        return $this->access->getSettingById($id);
    }
    public function getSettingByClef($clef)
    {
        return $this->access->getSettingByClef($clef);
    }
    public function getSettings($limit, $offset)
    {
        return $this->access->getSettings($limit, $offset);
    }
    public function addSetting($setting)
    {
        return $this->access->addSetting($setting);
    }
    public function updateSetting($setting)
    {
        return $this->access->updateSetting($setting);
    }
}