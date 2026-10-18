@extends('layouts.app')

@section('title', 'Campi, asistente virtual — Mercam')

@section('content')
<div class="container" style="max-width: 1100px; padding: 40px 20px 60px;">

    <div class="row g-4 align-items-start">
        <div class="col-lg-5">
            <h1 style="font-family: Georgia, 'Times New Roman', serif; color:#214d13;">Conoce a Campi 🌱</h1>
            <p style="color:#5c544d; line-height:1.7;">
                Campi es el asistente virtual de Mercam. Usa inteligencia artificial (Google Gemini) para responder
                preguntas sobre <strong>los productos publicados</strong> en el catálogo y sobre <strong>cómo usar la plataforma</strong>:
                registrarte, comprar, publicar productos, seguir tus pedidos y más.
            </p>

            <h2 style="font-size:18px; color:#2b1c11; margin-top:24px;">Puedes preguntarle</h2>
            <ul style="color:#5c544d; line-height:1.8; padding-left:20px;">
                <li>¿Qué verduras hay disponibles y a qué precio?</li>
                <li>¿Quién vende papa y cuánto stock queda?</li>
                <li>¿Cómo publico mi cosecha como productor?</li>
                <li>¿Cómo cancelo un producto de mi pedido?</li>
            </ul>

            <h2 style="font-size:18px; color:#2b1c11; margin-top:24px;">Cómo funciona</h2>
            <ol style="color:#5c544d; line-height:1.8; padding-left:20px;">
                <li>Tu pregunta llega a nuestro servidor Laravel (la clave de la IA nunca sale del servidor).</li>
                <li>Campi busca en su base de conocimiento: el manual de Mercam y el catálogo real de productos publicados.</li>
                <li>Envía a Gemini solo esa información y sus reglas: responder únicamente sobre Mercam y no inventar datos.</li>
                <li>Te devuelve una respuesta corta, con enlaces a los productos.</li>
            </ol>
        </div>

        <div class="col-lg-7">
            <x-chatbot modo="pagina" />
        </div>
    </div>

    <section id="etica" class="form-edit-post" style="text-align:left; margin-top:40px; background:#fff; border:1px solid #e8e0d4; border-radius:18px; padding:30px 28px;">
        <h2 style="font-family: Georgia, 'Times New Roman', serif; color:#214d13;">Campi y la Declaración de la Persona Transhumana</h2>
        <p style="color:#5c544d; line-height:1.7;">
            La Universidad de Cundinamarca, en su Modelo Educativo Digital Transmoderno (MEDIT), describe a la persona transhumana como
            alguien que trasciende su condición sin dejar de ser humano: autónoma, libre y responsable, protagonista de su proyecto de vida
            y agente de transformación de su entorno, la sociedad y la naturaleza. La declaración advierte que la ciencia y la tecnología
            no deben vulnerar la dignidad humana ni convertirse en instrumento de dominación, exclusión o privilegio. Así aplicamos esos principios en Campi:
        </p>
        <div class="row g-3" style="margin-top:6px;">
            <div class="col-md-6 col-lg-3">
                <strong style="color:#2b1c11;">Tecnología al servicio de la persona</strong>
                <p style="color:#5c544d; font-size:14px; line-height:1.6;">Campi orienta; no decide. Informa precios, stock y pasos para que cada persona elija con autonomía.</p>
            </div>
            <div class="col-md-6 col-lg-3">
                <strong style="color:#2b1c11;">Inclusión, no exclusión</strong>
                <p style="color:#5c544d; font-size:14px; line-height:1.6;">Lenguaje sencillo para productores rurales con poca experiencia digital, disponible en cualquier momento y sin costo.</p>
            </div>
            <div class="col-md-6 col-lg-3">
                <strong style="color:#2b1c11;">Ética y transparencia</strong>
                <p style="color:#5c544d; font-size:14px; line-height:1.6;">Se presenta como IA, responde solo con datos verificados de Mercam, reconoce lo que no sabe y no pide datos personales.</p>
            </div>
            <div class="col-md-6 col-lg-3">
                <strong style="color:#2b1c11;">Comunidad y naturaleza</strong>
                <p style="color:#5c544d; font-size:14px; line-height:1.6;">Fortalece el comercio justo y directo entre el campo de Ubaté y la ciudad, valorando el trabajo campesino y los productos de la tierra.</p>
            </div>
        </div>
    </section>
</div>
@endsection
