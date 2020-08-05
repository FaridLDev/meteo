<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MeteoController extends AbstractController
{
    /**
     * @Route("/", name="meteo")
     */
    public function index(Request $request)
    {
        // On récupère notre paramètre ville
        $ville = $request->query->get('ville');

        // la requête l'URL //https://openweathermap.org
        $url = "http://api.openweathermap.org/data/2.5/weather?q=" . $ville . "&appid=d2d788642ee95620b06b8e4ae247842b";
// Lit tout un fichier dans une chaîne
        $data = @file_get_contents($url);

        //si nous avons reçu des données
        if ($data) {
            // décoder les données reçues -> décode une chaîne JSON
            $dataJson = json_decode($data);

            // echo '<pre>' , var_dump($dataJson) , '</pre>';
            // sélectionnons seulement les données nécessaires

            $city = $dataJson->name;
            $temperature_kelvin = $dataJson->main->temp;
            $temperature_min_kelvin = $dataJson->main->temp_min;
            $temperature_max_kelvin = $dataJson->main->temp_max;
            $pressure = $dataJson->main->pressure;
            $humidity = $dataJson->main->humidity;
            $wind_mil = $dataJson->wind->speed;


            $temperature_celsius = $temperature_kelvin - 273.15;
            $temperature_min_celsius = $temperature_min_kelvin - 273.15;
            $temperature_max_celsius = $temperature_max_kelvin - 273.15;
            $wind_km = $wind_mil * 1.61;


            return $this->render('meteo/index.html.twig', [
                'data' => $data,
                'city' => $city,
                'temperature_celsius' => $temperature_celsius,
                'temperature_min_celsius' => $temperature_min_celsius,
                'temperature_max_celsius' => $temperature_max_celsius,
                'pressure' => $pressure,
                'humidity' => $humidity,
                'wind_km' => $wind_km]);
        } else {//si nous n'avons pas reçu des données  variable ville soit null soit erroné

            return $this->render('meteo/index.html.twig', ['ville' => $ville]);
        }


    }

}
