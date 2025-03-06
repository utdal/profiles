<?php

namespace App\Http\Livewire;

use App\Http\Requests\Concerns\HasImageUploads;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\TemporaryUploadedFile;

class ImagePicker extends Component
{
    use WithFileUploads, HasImageUploads, AuthorizesRequests;

    /**
     * Uploaded image file
     * @var TemporaryUploadedFile
     */ 
    public $image;
    
    /**
     * Existing image URL (if the model already has an image)
     * @var string|null
     */ 
    public $existing_image_url;
    
    /**
     * A kebab-case unique identifier for each component
     * @var string
     * for the Livewire wire:key directive to identify the DOM elements when multiple instances of the component exist
     * Example: "banner-img"
     */ 
    public $custom_key;

    /**
     * Additional instructions for the image file selection 
     * @var string|null
     * Example: "This photo will appear on your profile page and as your application profile image - please use a high-quality image (300x300 pixels or larger)."
     * 
     */ 
    public $custom_msg;
    
    /**
     * Model instance that has an image is associated
     * @var \Illuminate\Database\Eloquent\Model
     */ 
    public $model;
    
    /**
     * Method on the model used to save the image
     * @var string|null
     */ 
    public $save_function;
    
    /**
     * Parameters passed to the save function
     * @var array|null
     */ 
    public $save_params;
    
    /**
     * The parameter name expected by the save function for the uploaded image
     * @var string
     */ 
    public $image_param_name;
    
    /**
     * Callback function to execute after saving the image
     * @var string|null
     */ 
    public $callback_function;
    
    /**
     * Parameters for the callback function
     * @var array|null
     */ 
    public $callback_params;
    
    /**
     * Additional blade partial view to display below the image preview
     * @var string|null
     */ 
    public $partial_view;
    
    /**
     * Route to redirect to after saving the image
     * @var string
     */ 
    public $redirect_route;
    
    /**
     * Flash message displayed after saving the image
     * @var string|null
     */ 
    public $message;
    
    /**
     * Authorization parameters for checking user permissions
     * @var array $auth_params
     */ 
    public $auth_params;

    public function mount() 
    {
        if (isset($this->save_function)) { // Validates save function only if set
            $this->validateCallUserFunc('save_function');
        }

        if (isset($this->callback_function)) { // Validates callback function only if set
            $this->validateCallUserFunc('callback_function');
        }
    }
    
    /**
     * Validates the image after a file has been selected and stores the updated image in the save function parameters for uploading
     */
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
        $this->authorize(...$this->auth_params);
        
        // Calls the model's save function if an image is uploaded
        if ($this->image) {
            $this->message = call_user_func([$this->model, $this->save_function], ...$this->save_params ?? []);
        }

        // If a callback function is set, execute it (e.g., updating related records)
        if (isset($this->callback_function)) {
            call_user_func([$this->model, $this->callback_function], ...$this->callback_params ?? []);
        }

        return redirect($this->redirect_route)->with('flash_message', $this->message);
    }

    /**
     * Validates that the provided method name exists on the model
     * Prevents runtime errors if an invalid method is supplied
     *
     * @param string $function_name
     * Name of the function to validate
     */
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

