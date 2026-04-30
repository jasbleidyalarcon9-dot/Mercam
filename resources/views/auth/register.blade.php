<x-guest-layout>

    <div class="contenedor-registro" style="max-width:700px; margin:50px auto;">

        <!-- 🔴 TEXTO -->
        <h2 style="color:#6b3e26; font-weight:bold;">
            MERCAM
        </h2>

        <p class="mt-3">
            La estrategia de Mercados Campesinos, impulsa la disponibilidad y suministro de alimentos saludables en términos de calidad, cuidado y variedad. Adicionalmente, se convierte en una vitrina de oportunidades para que los productores campesinos comercialicen sus productos a un buen precio y sin intermediarios, fortaleciendo la seguridad alimentaria.
        </p>

        <h5 style="color:#6b3e26; font-weight:bold;" class="mt-4">
            Autorización para el tratamiento de datos personales*
        </h5>

        <p style="font-size:14px; text-align:justify;">
            En cumplimiento de lo dispuesto en la Ley 1581 de 2012 y el Decreto 1377 de 2013, la Secretaría Distrital de Desarrollo Económico solicita su autorización para el tratamiento de sus datos personales.
        </p>

        <ul style="font-size:14px;">
            <li>Generar alertas y beneficios.</li>
            <li>Actualizar información.</li>
            <li>Enviar información de interés.</li>
            <li>Análisis estadístico.</li>
        </ul>

        <p style="font-size:14px;">
            El titular podrá ejercer sus derechos conforme a la normativa vigente.
        </p>

        <!-- 🔴 CHECKBOX -->
        <div style="margin-top:15px;">
            <label style="font-size:14px;">
                <input type="checkbox" name="terms" form="formRegistro" required>
                Autoriza el tratamiento de datos personales
            </label>
        </div>

        <!-- 🟢 FORMULARIO ABAJO -->
        <div style="margin-top:30px;">

            <form id="formRegistro" method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mt-3">
                    <x-input-label for="name" value="Nombre" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" required />
                </div>

                <div class="mt-3">
                    <x-input-label for="email" value="Correo" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" required />
                </div>

                <div class="mt-3">
                    <x-input-label for="password" value="Contraseña" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                </div>

                <div class="mt-3">
                    <x-input-label for="password_confirmation" value="Confirmar Contraseña" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600" href="{{ route('login') }}">
                        ¿Ya tienes cuenta?
                    </a>

                    <x-primary-button class="ms-4">
                        Registrarse
                    </x-primary-button>
                </div>

            </form>

        </div>

    </div>

</x-guest-layout>