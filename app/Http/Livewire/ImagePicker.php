<?php

namespace App\Http\Livewire;

use App\Http\Requests\Concerns\HasImageUploads;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\WithFileUploads;

class ImagePicker extends Component
{
    use WithFileUploads, HasImageUploads;

    public $image;
    public $existing_image_url;
    public $custom_key;
    public $custom_msg;
    public $model;
    public $save_function;
    public $save_params;
    public $image_param_name;
    public $callback_function;
    public $callback_params;
    public $partial_view;
    public $redirect_route;
    public $message;

    public function mount() 
    {
        $this->validateCallUserFunc('save_function');
        $this->validateCallUserFunc('callback_function');
    }
    
    public function updatedImage()
    {
        if ($this->image) {
            $this->validate(
                ['image' => "nullable|{$this->uploadedImageRules()}"],
            );
            
            $this->save_params[$this->image_param_name] = $this->image;
        }
    }

    public function save()
    {
        if ($this->image) {
            $this->message = call_user_func([$this->model, $this->save_function], ...$this->save_params ?? []);
        }

        if (isset($this->callback_function)) {
            call_user_func([$this->model, $this->callback_function], ...$this->callback_params ?? []);
        }

        return redirect()->route($this->redirect_route, $this->model)->with('flash_message', $this->message);
    }

    private function validateCallUserFunc($function_name)
    {
        $class_name = get_class($this->model);

        $this->validate([
            'model' => [
                'required',
                function ($attribute, $value, $fail) use ($class_name) {
                    if (!class_exists($class_name) || !is_subclass_of($class_name, Model::class)) {
                        $fail('Please contact the app admin.');
                        logger()->error("The class {$class_name} is not a valid Eloquent model.");
                    }
                }
            ],

            $function_name => [
                function ($attribute, $value, $fail) use ($class_name) {
                    /** @var string|null $value */
                    if (!is_callable($value, true) || !method_exists($class_name, $value)) {
                        $fail('Please contact the app admin.');
                        logger()->error("The method {$value} does not exist in {$class_name}.");
                    }
                }
            ],
        ]);
    }

    public function render()
    {   
        return view('livewire.image-picker');
    }
}

