<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CalculateController extends AbstractController
{
    #[Route('/_calculate', name: 'app_calculate')]
    public function index(Request $request)
    {
        $_POST['queue'] = $_POST['queue']??[];
        $isGetResuest = $request->isMethod('GET');
        $form =fn()=>
        $this->render('response.html.twig', [
            'queue' => json_encode($_POST['queue']??[]),
        ]);

        if($isGetResuest){
            return $form();
        }

        if (isset($_POST['submit'])) {
            $_POST['queue'] =
                [
                    ...json_decode($_POST['queue']??'{}', true),
                    [
                        'number1' => $_POST['number1'],
                        'number2' => $_POST['number2'],
                        'operation' => $_POST['operation']
                    ]
                ];
            echo "<p>В очереди: </p><ul>";
            foreach($_POST['queue'] as $val)
            {
                echo "<li>".$val['number1'].$val['operation'].$val['number2']."</li>";
            }
            echo"</ul>";
        }

        if(isset($_POST['calculate']))
        {
            $result = $this->calculate();
            $calculate = '';
            foreach ($result as $value)
            {
                $calculate .= $value;
            }
            echo $calculate;
            unset($_POST['queue']);
        }



        return $form();
    }

    private function calculate():array
    {

        // If the submit button has been pressed
        $result = [];
        foreach(json_decode($_POST['queue'], true) as ['number1' =>$_POST['number1'], 'number2' => $_POST['number2'], 'operation' => $_POST['operation'] ]){
            // Check number values
            if (is_numeric($_POST['number1']) && is_numeric($_POST['number2'])) {
                // Calculate total
                if ($_POST['operation'] == 'plus') {
                    $total = $_POST['number1'] + $_POST['number2'];
                }
                if ($_POST['operation'] == 'minus') {
                    $total = $_POST['number1'] - $_POST['number2'];
                }
                if ($_POST['operation'] == 'times') {
                    $total = $_POST['number1'] * $_POST['number2'];
                }
                if ($_POST['operation'] == 'divided by') {
                    $total = $_POST['number1'] / $_POST['number2'];
                }

                // Print total to the browser
                $result[] = "<h1>{$_POST['number1']} {$_POST['operation']} {$_POST['number2']} equals {$total}</h1>";
            } else {
                // Print error message to the browser
                $result[] = 'Numeric values are required';
            }
        }

        return $result;
    }
}
