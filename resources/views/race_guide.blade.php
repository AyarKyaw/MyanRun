@extends('layouts.master')

@section('title', 'Race Guide - MYANRUN')

@section('content')
    <div class="page-title">
        <div class="themeflat-container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-title-heading">
                        <h1 class="title">race guide</h1>
                    </div>
                    <div class="breadcrumbs">
                        <ul>
                            <li><a href="{{ url('/') }}">Homepage</a></li>
                            <li><i class="icon-Arrow---Right-2"></i></li>
                            <li><a>Race Guide</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="main-content blog-posts">
        <div class="themeflat-container">

            <div class="pdf-actions" style="margin-bottom: 20px; text-align: right;">
                <a href="{{ asset('images/pdf/bagan_marathon_faq_2.pdf') }}" class="tf-button style-1" download>
                    <i class="icon-download"></i> Download Race Guide (PDF)
                </a>
            </div>

            <!-- Canvas Container for PDF Pages -->
            <div id="pdf-container" class="pdf-preview"></div>

        </div>
    </section>
@endsection

@push('scripts')
<style>
    .pdf-preview {
        width: 100%;
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 8px;
    }
    .pdf-preview canvas {
        width: 100% !important;
        height: auto !important;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }
</style>

<!-- Load PDF.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
<script>
    const url = "{{ asset('images/pdf/bagan_marathon_faq_2.pdf') }}";
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

    const container = document.getElementById('pdf-container');

    pdfjsLib.getDocument(url).promise.then(pdf => {
        for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
            pdf.getPage(pageNum).then(page => {
                const viewport = page.getViewport({ scale: 1.5 });
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');

                canvas.height = viewport.height;
                canvas.width = viewport.width;

                container.appendChild(canvas);

                const renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };
                page.render(renderContext);
            });
        }
    }).catch(err => {
        console.error("PDF Loading Error: ", err);
        container.innerHTML = '<p class="text-center">Unable to load PDF preview. Please download the file directly.</p>';
    });
</script>
@endpush
