<?php

/**
 * Image Processor Class
 * @author Gilbor Camporazo Jr.
 */

namespace Gibocode\Ocr;

use \thiagoalessio\TesseractOCR\TesseractOCR;
use \Exception;

class ImageProcessor
{
    /**
     * @var string $imagePath
     */
    private $imagePath;

    /**
     * @var string $imageName
     */
    private $imageName;

    /**
     * Image Processor Constructor
     */
    public function __construct()
    {
        $this->setImagePath('images');
    }

    /**
     * Processes the image thru a request and gets the extracted text
     * @return string
     */
    public function run()
    {
        if (isset($_POST['image']))
        {
            $image = $_POST['image']; //base64 image

            $filePath = $this->saveImage($image, 'jpg');
            $text = $this->extractText($filePath);

            $card_numnber = $this->getCardNumber($text);

            echo json_encode(['text' => $text, 'card_number' => $card_numnber]);
            return;
        }

        throw new Exception("Invalid request.");
    }

    /**
     * Sets, checks, and creates image path
     * @param string $path
     * @return ImageProcessor
     */
    public function setImagePath($path)
    {
        if (!is_readable($path))
        {
            if (!file_exists($path)) mkdir($path, 0755);
            else chmod($path, 0755);
        }

        $this->imagePath = $path;
        return $this;
    }

    /**
     * Gets image path
     * @return string
     */
    public function getImagePath()
    {
        return $this->imagePath;
    }

    /**
     * Converts base64 image to an image file and saves it
     * @param string $base64 Image in base64 format
     * @param string $name
     * @param string $format
     * @return string
     */
    protected function saveImage($base64, $format)
    {
        $name = $this->generateImageName();
        $filePath = $this->getImagePath() . "/{$name}.{$format}";

        $handle = fopen($filePath, 'wb');
        $data = explode(',', $base64);

        fwrite($handle, base64_decode($data[1]));
        fclose($handle);

        $this->setImageName($name);

        return $filePath;
    }

    /**
     * Generates image name
     * @return string
     */
    protected function generateImageName()
    {
        date_default_timezone_set('Asia/Manila');
        $name = hash('md5', 'captured-image-' . date('n/j/Y H:i:s'));
        return $name;
    }

    /**
     * Sets image name
     * @param string $name
     * @return ImageProcessor
     */
    public function setImageName($name)
    {
        $this->imageName = $name;
        return $this;
    }

    /**
     * Gets image name
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * Extracts text from the specified file
     * @param string $imageFile
     * @return string
     */
    public function extractText($imageFile)
    {
        $executablePath = '/usr/local/Cellar/tesseract/4.0.0/bin/tesseract'; // Local installation path of Tesseract executable
        $extractedText = (new TesseractOCR($imageFile))
            ->lang('eng')
            ->executable($executablePath)
            ->run();

        return $extractedText;
    }

    /**
     * Gets the card number from the extracted text
     * @param string $text
     * @return string
     */
    protected function getCardNumber($text)
    {
        $card_numnber = '';
        $text = str_replace(' ', '', $text);    // Removes spaces from the text
        $pattern = "/(?:4[0-9]{12}(?:[0-9]{3})?|[25][1-7][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})/";

        if (count($contents = preg_split($pattern, $text)) > 0)
        {
            $exclude = $contents[0];
            $text = str_replace($exclude, '', $text);

            // Adds 3 spaces in between numbers
            for ($i = 0; $i < strlen($text); $i++)
            {
                $card_numnber .= substr($text, $i, 1);

                if (in_array($i, [3, 7, 11]))
                {
                    $card_numnber .= ' ';
                }
            }
        }

        return $card_numnber;
    }
}
