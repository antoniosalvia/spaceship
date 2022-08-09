<?php

    // Fabrik Pattern
    class Spaceship {

        private $name;
        private $color;
        private $engine;

        public function __construct($n, $c, $e) {
            $this->name = $n;
            $this->color = $c;
            $this->engine = $e;
            new CreateImage($this->name, $this->color);
        }
    }

    class CreateImage {

        private $w = 400; // Breite
        private $h = 200; // Höhe
        private $img;

        public function __construct($n, $c) {
            $this->setBackground();
            $this->createStars();
            $this-> createSpaceship($c);
            $this->setName($n);
            $this->saveImage();
        }

        private function setBackground() {
            $this->img = imagecreatetruecolor($this->w, $this->h);
            $black = imagecolorallocate($this->img, 0, 0, 0);
            imagefill($this->img, 0, 0, $black);
        }

        private function createStars() {
            $white = imagecolorallocate($this->img, 255, 255, 255);
            for ($i=1; $i <= 400; $i++) {
                $x = rand (0, $this->w);
                $y = rand(0, $this->h);
                imagesetpixel($this->img, $x, $y, $white);
                if($i == 100 OR $i == 300) imagefilter($this->img, IMG_FILTER_GAUSSIAN_BLUR, 1);
            }

        }

        private function createSpaceship($c) {
            // 255, 0 , 0
            list($r, $g, $b) = explode(',', $c);
            $color = imagecolorallocate($this->img, $r, $g, $b);
            // $array = [170, 10,    190, 40,    150, 40,    170 , 10];
            $array = [];
            while(count($array) <= 24) {
                $array[] = rand(0, 370); // x-Wert
                $array[] = rand(30, 170); // y-Wert
            }
            $points = count($array) / 2;
            imagefilledpolygon($this->img, $array, $points, $color);
        }


        private function setName($n) {
            $white = imagecolorallocate($this->img, 255, 255, 255);
            imagestring($this->img, 5, 10, 10, $n, $white);
        }

        private function saveImage() {
            imagepng($this->img, 'spaceship.png');
        }

        public function __destruct(){
            imagedestroy($this->img); // Aufräumen
        }
    }



    // Benutzung einer statischen Klasse (nicht als Objekt)
    class Factory {

        public static function create($name, $color, $engine) {
            return new Spaceship($name, $color, $engine);
        }
    }

    $color = '255, 0, 0';
    if(isset($_GET['color'])) { // aus input Feld
        // hexadezimal Farbwerte zu RGB-Werten umwandeln
        $color = str_replace('#', '', $_GET['color']);
        $arr = str_split($color, 2);
        $r = hexdec($arr[0]);
        $g = hexdec($arr[1]);
        $b = hexdec($arr[2]);
        $color = $r.','.$g.','.$b;
    }

    Factory::create('Enterprise', $color, 'Ionen');

?>