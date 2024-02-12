<?php
class gemini
{

    private $key = "";

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function generateResponse($text)
    {
        $api_key = $this->key;
        $text = $text;

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=' . $api_key;

        $data = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $text
                        ]
                    ]
                ]
            ]
        ];

        $json_data = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

        $response = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($response, true);

        // Verifica se a decodificação foi bem-sucedida
        if ($result !== null) {
            // Extrai o texto da resposta
            $generated_text = $result['candidates'][0]['content']['parts'][0]['text'];

            // Imprime o texto gerado
            return $generated_text;
        }
    }
}
