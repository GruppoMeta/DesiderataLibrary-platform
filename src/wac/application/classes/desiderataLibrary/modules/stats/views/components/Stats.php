<?php
class desiderataLibrary_modules_stats_views_components_Stats extends org_glizy_components_Component
{
    public function init()
    {
      $this->defineAttribute('eventToShow', false, '', COMPONENT_TYPE_STRING);
      $this->defineAttribute('graphicBorder', false, 1, COMPONENT_TYPE_NUMBER);
      parent::init();
    }

    public function process()
    {
      $arrayEventi = array();
      $eventToShow = $this->getAttribute( 'eventToShow' );
      $graphicBorder = $this->getAttribute( 'graphicBorder' );

      $idPublication = __Request::get('idPublication');

      if(!$idPublication)
      {
        $it = org_glizy_ObjectFactory::createModelIterator('desiderataLibrary.modules.stats.models.Model')
                        ->load('getEvents',
                            array(
                              'eventType' => $eventToShow,
        ));
      }
      else
      {
        $it = org_glizy_ObjectFactory::createModelIterator('desiderataLibrary.modules.stats.models.Model')
                        ->load('getEventsPublication',
                            array(
                              'eventType' => $eventToShow,
                              'idPublication' => $idPublication,
        ));
      }

      $param = org_glizy_ObjectFactory::createModelIterator('desiderataLibrary.modules.stats.models.Model')
                      ->load('getParam',
                        array(
                          'eventType' => $eventToShow,
      ));

      //ottengo i parametri possibili per header grafico e preparo header
      $headParam = array();
      $header = 'Data';
      $series = '<h3>Seleziona/Deseleziona linee da visualizzare</h3><ul>';
      $countSeries = 0;

      foreach ($param as $key => $value) {
        $headParam[] = $this->t($value->eventStats_parametro);
        $header .= ",".$this->t($value->eventStats_parametro);
        $series .= '<li><input checked="checked" type="checkbox" id="'.$countSeries.'" onClick="change(this)"><span>'.$this->t($value->eventStats_parametro).'</span></li>';
        $countSeries++;
      }
      $header .= "\n";

      if ($countSeries==1) {
        $series = '';
      }

      //Estraggo quantità di eventi di ogni tipo
      foreach ($it as $key => $value) {
        $parametro = $this->t($value->eventStats_parametro);
        list($date, $time) = explode(' ', $value->eventStats_datetime);
        list($dd, $mm, $aa) = explode('/', $date);
        $keyData = $aa.'/'.$mm.'/'.$dd;
        $arrayEventi[$keyData][$parametro] += 1;
      }

      $dati = "";
      foreach ($arrayEventi as $key => $value) {
        $dati .= $key.",";
        foreach ($headParam as $p) {
          $v = ($value[$p]) ? $value[$p] : 0;
          $start = ($v !== 0) ? $v-$graphicBorder : 0 ;
          $dati .= $start.";".$v.";".($v+$graphicBorder).",";
        }
        $dati = rtrim($dati,",");
        $dati .= "\n";
      }
      $this->_content['dati'] = $header.$dati;
      $this->_content['series'] = $series."</ul>";
    }

    function t($string)
    {
      return str_replace(
        array(
          "success",
          "not-valid",
          "already-used",
          "error",
          "read",
          "generated",
          "used"
        ),
        array(
          "Successo",
          "Non valido",
          "Già utilizzato",
          "Fallimento",
          "Lettura",
          "Generato",
          "Utilizzato"
        ),
        $string);
    }
}
