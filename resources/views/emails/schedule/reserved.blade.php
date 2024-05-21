<p>Hola {{ $mailData['name'] }},</p>
<p>Su reserva ha sido aprobada.</p>
<p>Aquí hay un enlace de Zoom donde puede registrarse para recibir tratamiento.</p>
<p>{{ $mailData['link'] }}</p>
<p>Thanks,</p>
<p>{{ config('app.name') }}</p>
