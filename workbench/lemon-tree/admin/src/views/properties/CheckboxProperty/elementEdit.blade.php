{{ Form::checkbox($name, 1, $value ? true : false, array($readonly ? 'readonly' : null, 'id' => $name)) }} {{ Form::label($name, $title) }}