<?php

namespace App\Http\Controllers;

use App\Models\ValidacionQr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class ValidacionQrController extends Controller
{
    public function index()
    {
        $validaciones = ValidacionQr::latest()->paginate(10);

        foreach ($validaciones as $v) {
            $result = Builder::create()
                ->writer(new PngWriter())
                ->data('https://www.ccisur.org/4236-2/?token=' . $v->token)
                ->size(200)
                ->margin(5)
                ->build();

            $v->qr_base64 = base64_encode($result->getString());
        }

        return view('validaciones_qr.index', compact('validaciones'));
    }

    public function create()
    {
        return view('validaciones_qr.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_formacion' => 'required|string|max:255',
            'fecha_formacion' => 'nullable|date',
            'anio' => 'nullable|digits:4|integer',
        ]);

        $token = Str::uuid()->toString();

        ValidacionQr::create([
            'nombre_formacion' => $request->nombre_formacion,
            'fecha_formacion' => $request->fecha_formacion,
            'anio' => $request->anio,
            'token' => $token,
            'activo' => true,
        ]);

        return redirect()
            ->route('validaciones_qr.index')
            ->with('success', 'Registro QR creado correctamente.');
    }

    public function show(ValidacionQr $validaciones_qr)
    {
        return view('validaciones_qr.show', compact('validaciones_qr'));
    }

    public function edit(ValidacionQr $validaciones_qr)
    {
        return view('validaciones_qr.edit', compact('validaciones_qr'));
    }

    public function update(Request $request, ValidacionQr $validaciones_qr)
    {
        $request->validate([
            'nombre_formacion' => 'required|string|max:255',
            'fecha_formacion' => 'nullable|date',
            'anio' => 'nullable|digits:4|integer',
            'activo' => 'required|boolean',
        ]);

        $validaciones_qr->update([
            'nombre_formacion' => $request->nombre_formacion,
            'fecha_formacion' => $request->fecha_formacion,
            'anio' => $request->anio,
            'activo' => $request->activo,
        ]);

        return redirect()
            ->route('validaciones_qr.index')
            ->with('success', 'Registro QR actualizado correctamente.');
    }

    public function destroy(ValidacionQr $validaciones_qr)
    {
        $validaciones_qr->delete();

        return redirect()
            ->route('validaciones_qr.index')
            ->with('success', 'Registro QR eliminado correctamente.');
    }

    public function validar($token)
    {
        $registro = ValidacionQr::where('token', $token)->first();

        if (!$registro) {
            return view('validaciones_qr.validacion_publica', [
                'valido' => false,
                'registro' => null,
            ]);
        }

        return view('validaciones_qr.validacion_publica', [
            'valido' => $registro->activo,
            'registro' => $registro,
        ]);
    }

    public function descargarQr($token)
    {
        $registro = ValidacionQr::where('token', $token)->firstOrFail();

        $url = 'https://www.ccisur.org/4236-2/?token=' . $registro->token;

        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($url)
            ->size(300)
            ->margin(10)
            ->build();

        return response()->streamDownload(
            function () use ($result) {
                echo $result->getString();
            },
            'qr-' . $registro->token . '.png',
            [
                'Content-Type' => 'image/png',
            ]
        );
    }

    public function qrPreview($token)
    {
        $registro = ValidacionQr::where('token', $token)->firstOrFail();

        $svg = QrCode::format('svg')
            ->size(300)
            ->generate('https://www.ccisur.org/4236-2/?token=' . $registro->token);

        return response(trim($svg), 200)
            ->header('Content-Type', 'image/svg+xml');
    }
}
