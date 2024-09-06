<?php 

class Calendar 
{
    private $dateDebut;
    private $dateFin;
    private $roomCalendar;
    private $immutable;

    public function __construct($dateDebut, $dateFin, $roomCalendar, $immutable)
    {
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->roomCalendar = $roomCalendar;
        $this->immutable = $immutable;
    }
    private function isRoomRed($currentDay)
    {
        foreach($this->roomCalendar as $calendarDate)
        {
            if($currentDay >= $calendarDate[0] && $currentDay <= $calendarDate[1])
            {
                return true;
            }
        }
        return false;
    }
    public function toString()
    {
        $html = "
                <p class = 'block'>Période du " . $this->dateDebut->format("d-m-Y") . " au " . $this->dateFin->format("d-m-Y") . "</p>";
                if(!$this->immutable)
                {
                    $html .= "<button onclick = 'previousPeriod();' class = 'button is-left'>Précédent</button>";
                    $html .= "<button onclick = 'nextPeriod();' class = 'button is-right'>Suivant</button>";
                }

        $html .=    "<table id = 'calendar' class = 'table'>
                    <thead>
                        <tr>
                            <th>Lundi</th>
                            <th>Mardi</th>
                            <th>Mercredi</th>
                            <th>Jeudi</th>
                            <th>Vendredi</th>
                            <th>Samedi</th>
                            <th>Dimanche</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>";
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($this->dateDebut, $interval, $this->dateFin);
        $startingDayOfWeek = $this->dateDebut->format('N');
        $currentDayOfWeek = $startingDayOfWeek;
        for($i = 1; $i < $startingDayOfWeek; $i++)
        {
            $html .= "<th>.</th>";
        }
        foreach($period as $day)
        {
            
            $currentDay = strtotime($day->format('Y-m-d'));
            if($this->isRoomRed($currentDay))
            {
                $html .= "<th><p style = 'color: red;'>" . $day->format('d') . "</p></th>";
            }
            else 
            {
                $html .= "<th><p style = 'color: green;'>" . $day->format('d') . "</p></th>";
            }
            if($currentDayOfWeek >= 7)
            {
                $html .= "</tr><tr>";
                $currentDayOfWeek = 0;
            }
            $currentDayOfWeek++;
        }
        $html .= "     
                    </tbody>
                </table>
                <p style = 'color: red;' class = 'block'>Rouge : Chambre réservée.</p>
                <p style = 'color: green;' class = 'block'>Vert : Chambre libre.</p>";
        return $html;
    }
}