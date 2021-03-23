<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use App\Models\Email;
use Carbon\Carbon;
use Exception;

class EmailsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(){
        return Email::all();
    }

    public function store(Request $request){

        $messages = [
            'nome.required' => 'Informe seu nome completo',
            'email.required' => 'Informe um e-mail',
            'email.email' => 'Informe um e-mail válido',
            'telefone.required' => 'Informe um telefone',
            'cargo.required' => 'Informe um cargo',
            'escolaridade.required' => 'Informe sua escolaridade',
            'arquivo.required' => 'Selecione um arquivo',
            'arquivo.mimes' => 'O arquivo selecionado não é permitido'
        ];

        $this->validate($request, [
            'nome' => 'required|different:null',
            'email' => 'required|email|different:null',
            'telefone' => 'required|different:null',
            'cargo' => 'required|different:null',
            'escolaridade' => 'required|different:null',
            'arquivo' => 'required|mimes:doc,docx,pdf|different:null'
        ], $messages);

        try{

            $dataCreate = $request->all();

            $dataCreate['ip'] = $request->ip();
            $dataCreate['data_envio'] = Carbon::now();

            $data = "Email enviado pelo CV Send";

            Mail::send('page', ['data'=>$data], function($message) use ($dataCreate){
                $message
                ->to('edvaldojunodi@gmail.com', $dataCreate['email'])
                ->subject('Email do CV Send')
                ->attach($dataCreate['arquivo'], [
                    'as' => 'cv-send.pdf',
                    'mime' => 'application/pdf',
                ]);
            });

            Email::create($dataCreate);
            return response()->json('E-mail enviado com sucesso', 200);

        }catch(Exception $error){
            return response()->json($error);
        }
    }
}
