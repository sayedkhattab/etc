@extends('admin.layouts.app')

@section('title', 'تعديل سؤال')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1>تعديل سؤال</h1>
    <a href="{{ route('admin.questions.index', [], false) }}?assessment_id={{ $assessment->id }}" class="btn btn-secondary"><i class="bi bi-arrow-right"></i> العودة</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.questions.update', $question) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">نوع السؤال <span class="text-danger">*</span></label>
                <select name="question_type_id" class="form-select @error('question_type_id') is-invalid @enderror" required>
                    @foreach($questionTypes as $type)
                        <option value="{{ $type->id }}" {{ old('question_type_id', $question->question_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>
                @error('question_type_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">نص السؤال <span class="text-danger">*</span></label>
                <textarea name="question_text" class="form-control @error('question_text') is-invalid @enderror" rows="4" required>{{ old('question_text', $question->question_text) }}</textarea>
                @error('question_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- خيارات سؤال اختيار من متعدد -->
            <div id="mcq-options" class="mb-3" style="display:none;">
                <label class="form-label">الخيار الصحيح <span class="text-danger">*</span></label>
                <div id="options-wrapper">
                    <!-- option template -->
                    @if($question->options && ($question->questionType->name === 'اختيار من متعدد' || $question->questionType->name === 'Multiple Choice'))
                        @foreach($question->options as $index => $option)
                            <div class="input-group mb-2">
                                <div class="input-group-text">
                                    <input type="radio" name="correct_option" value="{{ $index }}" class="form-check-input mt-0" {{ $option->is_correct ? 'checked' : '' }}>
                                </div>
                                <input type="text" name="options[]" class="form-control" placeholder="نص الخيار" value="{{ $option->option_text }}" required>
                            </div>
                        @endforeach
                    @endif
                </div>
                <button type="button" class="btn btn-sm btn-secondary mt-2" id="add-option"><i class="bi bi-plus-circle"></i> إضافة خيار</button>
            </div>

            <!-- صحيح أو خطأ -->
            <div id="tf-options" class="mb-3" style="display:none;">
                <label class="form-label">الإجابة الصحيحة <span class="text-danger">*</span></label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="correct_answer" id="trueOption" value="true" 
                            {{ ($question->options && ($question->questionType->name === 'صح وخطأ' || $question->questionType->name === 'True/False') && $question->options->where('is_correct', true)->first() && ($question->options->where('is_correct', true)->first()->option_text === 'صح' || $question->options->where('is_correct', true)->first()->option_text === 'True')) ? 'checked' : '' }}>
                        <label class="form-check-label" for="trueOption">صح</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="correct_answer" id="falseOption" value="false"
                            {{ ($question->options && ($question->questionType->name === 'صح وخطأ' || $question->questionType->name === 'True/False') && $question->options->where('is_correct', true)->first() && ($question->options->where('is_correct', true)->first()->option_text === 'خطأ' || $question->options->where('is_correct', true)->first()->option_text === 'False')) ? 'checked' : '' }}>
                        <label class="form-check-label" for="falseOption">خطأ</label>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">المحتوى المرتبط (اختياري)</label>
                <select name="content_id" class="form-select @error('content_id') is-invalid @enderror">
                    <option value="">بدون</option>
                    @foreach($contents as $content)
                        <option value="{{ $content->id }}" {{ old('content_id', $question->content_id) == $content->id ? 'selected' : '' }}>{{ $content->title }}</option>
                    @endforeach
                </select>
                @error('content_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">الدرجة <span class="text-danger">*</span></label>
                <input type="number" name="points" class="form-control @error('points') is-invalid @enderror" min="1" value="{{ old('points', $question->points) }}" required>
                @error('points')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">تفسير عند الإجابة الخاطئة (اختياري)</label>
                <textarea name="explanation" class="form-control @error('explanation') is-invalid @enderror" rows="3">{{ old('explanation', $question->explanation) }}</textarea>
                @error('explanation')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> تحديث</button>
        </form>
    </div>
</div>
@push('scripts')
<script>
    const typeSelect = document.querySelector('select[name="question_type_id"]');
    const mcqBox = document.getElementById('mcq-options');
    const tfBox  = document.getElementById('tf-options');
    const optionsWrapper = document.getElementById('options-wrapper');
    const addOptionBtn   = document.getElementById('add-option');

    function updateVisibility() {
        const selected = typeSelect.options[typeSelect.selectedIndex]?.text;
        mcqBox.style.display = (selected === 'اختيار من متعدد' || selected === 'Multiple Choice') ? 'block' : 'none';
        tfBox.style.display  = (selected === 'صح وخطأ' || selected === 'True/False') ? 'block' : 'none';
        
        // تسجيل للتصحيح
        console.log('نوع السؤال المحدد:', selected);
        console.log('عرض خيارات الاختيار من متعدد:', mcqBox.style.display);
        console.log('عرض خيارات صح وخطأ:', tfBox.style.display);
    }

    function addOption(value = '', index = null) {
        const idx = index !== null ? index : optionsWrapper.children.length;
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `<div class="input-group-text">
            <input type="radio" name="correct_option" value="${idx}" class="form-check-input mt-0">
        </div>
        <input type="text" name="options[]" class="form-control" placeholder="نص الخيار" value="${value}" required>`;
        optionsWrapper.appendChild(div);
    }

    typeSelect.addEventListener('change', updateVisibility);
    addOptionBtn.addEventListener('click', () => addOption());

    // init
    updateVisibility();
    // افتراضيًا خياران فارغان عند اختيار اختيار من متعدد لأول مرة
    typeSelect.addEventListener('change', () => {
        const selected = typeSelect.options[typeSelect.selectedIndex]?.text;
        if((selected === 'اختيار من متعدد' || selected === 'Multiple Choice') && optionsWrapper.children.length === 0){
            addOption();
            addOption();
        }
    });

    // التأكد من وجود خيار صحيح محدد عند الإرسال
    document.querySelector('form').addEventListener('submit', function(e) {
        const selected = typeSelect.options[typeSelect.selectedIndex]?.text;
        
        if(selected === 'اختيار من متعدد' || selected === 'Multiple Choice') {
            // التحقق من وجود خيار صحيح محدد
            const hasSelectedOption = document.querySelector('input[name="correct_option"]:checked');
            if(!hasSelectedOption) {
                e.preventDefault();
                alert('يرجى تحديد الخيار الصحيح للسؤال');
            }
        } else if(selected === 'صح وخطأ' || selected === 'True/False') {
            // التحقق من تحديد إجابة صح أو خطأ
            const hasSelectedTrueFalse = document.querySelector('input[name="correct_answer"]:checked');
            if(!hasSelectedTrueFalse) {
                e.preventDefault();
                alert('يرجى تحديد الإجابة الصحيحة (صح أو خطأ)');
            }
        }
    });
</script>
@endpush
@endsection 