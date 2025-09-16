<?php

namespace App;

class PdfGenerationResult
{
    public function __construct(
        public bool $success,
        public ?string $filename,
        public ?string $path,
        public ?string $job_id,

    ) {}
    
    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'filename' => $this->filename,
            'path' => $this->path,
            'job_id' => $this->job_id,
        ];
    }
}