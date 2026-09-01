<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Equipment Tag: {{ $equipment->name }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            body { margin: 0; padding: 0; background: white; display: block; }
            .no-print { display: none !important; }
            .print-container { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; }
            .tag-card { border: 2px solid black !important; box-shadow: none !important; margin: 0 auto; page-break-inside: avoid; }
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center">
    
    <div class="no-print absolute top-6 right-6 flex gap-3">
        <a href="{{ route('equipment.show', $equipment) }}" class="btn btn-secondary bg-white text-slate-700 px-4 py-2 rounded-lg font-semibold shadow-sm border border-slate-200 hover:bg-slate-50 transition-colors">Back</a>
        <button onclick="window.print()" class="btn btn-primary bg-red-600 text-white px-4 py-2 rounded-lg font-semibold shadow-md hover:bg-red-700 transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0v2.796c0 .12.048.235.134.32l2.36 2.36c.085.085.2.134.32.134h5.372c.12 0 .235-.048.32-.134l2.36-2.36a.453.453 0 00.134-.32V7.07z"/></svg>
            Print Tag
        </button>
    </div>

    <div class="print-container">
        <div class="tag-card bg-white border-2 border-slate-900 rounded-2xl p-8 w-80 text-center shadow-xl">
            <div class="mb-5">
                <h2 class="text-xl font-bold text-slate-900">{{ config('app.name', 'IT Helpdesk') }} Asset</h2>
                <p class="text-[11px] font-bold tracking-widest uppercase text-slate-500 mt-1">Property of Hotel</p>
            </div>
            
            <div class="flex justify-center mb-6">
                <div class="p-3 border-2 border-slate-100 rounded-xl">
                    {!! QrCode::size(160)->generate(route('equipment.show', $equipment)) !!}
                </div>
            </div>

            <div>
                <p class="font-bold text-lg text-slate-800 leading-tight mb-2">{{ $equipment->name }}</p>
                <div class="inline-block bg-slate-100 py-1.5 px-3 rounded-lg border border-slate-200">
                    <p class="text-sm font-mono text-slate-700 tracking-wider">{{ $equipment->serial_number }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
