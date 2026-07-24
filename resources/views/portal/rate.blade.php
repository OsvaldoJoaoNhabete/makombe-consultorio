<x-layouts.patient title="Avaliar Consulta">

    <div class="mb-6">
        <a href="{{ route('patient.consultations') }}" class="inline-flex items-center gap-2 text-purple-600 hover:text-purple-800 font-medium">
            <i class="fas fa-arrow-left"></i> Voltar para Minhas Consultas
        </a>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-100 rounded-full mb-4">
                    <i class="fas fa-star text-3xl text-yellow-500"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Avalie o seu Atendimento</h1>
                <p class="text-gray-600">
                    Consulta com <strong>Dr(a). {{ $consultation->doctor->name }}</strong><br>
                    <span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($consultation->scheduled_at)->format('d/m/Y \à\s H:i') }}</span>
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                    <ul class="text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li><i class="fas fa-exclamation-circle mr-1"></i>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('patient.consultations.rate', $consultation->id) }}" class="space-y-6">
                @csrf

                <!-- Classificação por Estrelas -->
                <div class="text-center">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Como foi a sua experiência?</label>
                    <div class="flex justify-center gap-2" id="starRating">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button" 
                                    class="star-btn text-4xl text-gray-300 hover:text-yellow-400 transition focus:outline-none" 
                                    data-value="{{ $i }}" 
                                    onclick="setRating({{ $i }})">
                                <i class="fas fa-star"></i>
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" required>
                    <p id="ratingText" class="text-sm text-purple-600 font-medium mt-2 h-5"></p>
                </div>

                <!-- Comentário Opcional -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Deixe um comentário (Opcional)
                    </label>
                    <textarea name="comment" rows="4" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none transition"
                              placeholder="Conte-nos o que achou do atendimento, instalações, pontualidade, etc."></textarea>
                </div>

                <!-- Botões -->
                <div class="flex gap-4 pt-4">
                    <button type="submit" id="submitBtn" disabled
                            class="flex-1 py-3 px-4 bg-purple-600 hover:bg-purple-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold rounded-xl shadow-lg transition flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane"></i> Enviar Avaliação
                    </button>
                    <a href="{{ route('patient.consultations') }}" class="flex-1 py-3 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const ratingTexts = [
            '',
            'Muito Insatisfeito',
            'Insatisfeito',
            'Neutro',
            'Satisfeito',
            'Muito Satisfeito'
        ];

        function setRating(value) {
            // Atualizar input hidden
            document.getElementById('ratingInput').value = value;
            
            // Atualizar texto
            document.getElementById('ratingText').textContent = ratingTexts[value];
            
            // Atualizar cor das estrelas
            const stars = document.querySelectorAll('.star-btn');
            stars.forEach((star, index) => {
                const icon = star.querySelector('i');
                if (index < value) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            });

            // Habilitar botão de envio
            document.getElementById('submitBtn').disabled = false;
        }
    </script>

</x-layouts.patient>