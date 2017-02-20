<?php

namespace mhndev\pusher;

use mhndev\pusher\exceptions\EmptyMessageDataException;
use mhndev\pusher\exceptions\InvalidArgumentException;
use mhndev\pusher\interfaces\iMessage;

/**
 * Class aMessage
 * @package mhndev\pusher
 */
abstract class aMessage implements iMessage
{

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $data;

    /**
     * @var array|\Traversable|null
     */
    protected $options;

    /**
     * @var string
     */
    protected $toString = null;


    const DEFAULT_NAMESPACE = 'default';


    /**
     * aMessage constructor.
     *
     * @param null $namespace
     * @param string $data
     * @param array $options
     *
     * @throws EmptyMessageDataException
     * @throws InvalidArgumentException
     */
    function __construct($data, $namespace = null, $options = [])
    {
        if(empty($data)){
            throw new EmptyMessageDataException;
        }

        if ($data instanceof \Traversable) {
            $data = iterator_to_array($data);

        }
        elseif (is_array($data)) {
            //do nothing
        }

        elseif (is_string($data)){
            //do nothing
        }

        elseif (is_object($data) && method_exists($data, '__toString')) {
            $data = $data->__toString();
        }

        else {
            throw new InvalidArgumentException(sprintf('data should be string or be array or
                 be instance of \Traversable or at least be an object with __toString method'));
        }

        $this->namespace = $namespace ? $namespace : static::DEFAULT_NAMESPACE;
        $this->data      = $data;
        $this->options   = $options;
    }

    /**
     * @return string
     */
    function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    function getData()
    {
        return $this->data;
    }


    /**
     * @return array|\Traversable|null
     */
    function getOptions()
    {
        return $this->options;
    }



    /**
     * @param string $namespace
     * @return $this
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * @param string $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param array|null|\Traversable $options
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }


    /**
     * @return string
     */
    public function __toString()
    {
        if(!empty($this->toString)){
            return $this->toString;
        }

        $arrayPresentation = [
            'namespace' => $this->namespace,
            'data'      => $this->data
        ];

        if(!empty($this->options['params'])){
            foreach ($this->options['params'] as $param_key => $param_value){
                $arrayPresentation[$param_key] = $param_value;
            }
        }

        return $this->toString = json_encode($arrayPresentation);
    }

    /**
     * @return string
     */
    public function getToString()
    {
        return $this->toString = !empty($this->toString) ? $this->toString : $this->__toString();
    }

    /**
     * @param string $toString
     * @return $this
     */
    public function setToString($toString)
    {
        $this->toString = $toString;

        return $this;
    }


}
