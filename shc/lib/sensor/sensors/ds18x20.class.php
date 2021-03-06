<?php

namespace SHC\Sensor\Sensors;

//Imports
use SHC\Sensor\AbstractSensor;
use RWF\Date\DateTime;

/**
 * DS18x20 Sensor
 * 
 * @author     Oliver Kleditzsch
 * @copyright  Copyright (c) 2014, Oliver Kleditzsch
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since      2.0.0-0
 * @version    2.0.0-0
 */
class DS18x20 extends AbstractSensor {
    
    /**
     * Temperatur
     *  
     * @var Float 
     */
    protected $temperature = 0.0;
    
    /**
     * Temperatur Anzeigen
     * 
     * @var Integer
     */
    protected $temperatureVisibility = 1;
    
    /**
     * @param Array  $values   Sensorwerte
     */
    public function __construct(array $values = array()) {
        
        if(count($values) == 5) {
            
            $this->oldValues = $values;
            $this->temperature = $values[0]['temp'];
            $this->time = $values[0]['time'];
        }
    }
    
    /**
     * gibt den Aktuellen Temperaturwert zurueck
     * 
     * @return Float
     */
    public function getTemperature() {
        
        return $this->temperature;
    }
    
    /**
     * setzt die Sichtbarkeit der Temperatur
     * 
     * @param  Integer $visibility Sichtbarkeit
     * @return \SHC\Sensor\Sensors\DS18x20
     */
    public function temperatureVisibility($visibility) {
        
        $this->temperatureVisibility = $visibility;
        return $this;
    }
    
    /**
     * gibt die Sichtbarkeit der Temperatur an
     * 
     * @return Boolean
     */
    public function isTemperatureVisible() {
        
        return ($this->temperatureVisibility == 1 ? true : false);
    }
    
    /**
     * setzt den aktuellen Sensorwert und schiebt ih in das Werte Array
     * 
     * @param Float $temperature Temperatur
     */
    public function pushValues($temperature) {
        
        $date = DateTime::now();
        
        //alte Werte Schieben
        array_unshift($this->oldValues, array('temp' => $temperature, 'time' => $date));
        //mehr als 5 Werte im Array?
        if(isset($this->oldValues[5])) {
            
            //aeltesten Wert loeschen
            unset($this->oldValues[5]);
        }
        
        //Werte setzten
        $this->temperature = $temperature;
        $this->time = $date;
        $this->isModified = true;
    }
}
