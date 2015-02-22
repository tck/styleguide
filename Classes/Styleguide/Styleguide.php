<?php 

namespace Thopra\Styleguide;

Class Styleguide {
	
	/**
	 * @var string
	 */
	protected $templateDir = '';	

	/**
	 * @var string
	 */
	protected $defaultTemplateDir = 'Templates';

	/**
	 * @var mixed
	 */
	protected $ref = false;

	/**
	 * @var string
	 */
	protected $src = "default";

	/**
	 * @var string
	 */
	protected $title = '';

	/**
	 * @var string
	 */
	protected $sources = array();


	/**
	 * Constructor
	 *
	 * @param mixed $paths
	 * @return void
	 */
	public function __construct($path, $title = 'Styleguide') 
	{
		$this->setTemplateDir($this->defaultTemplateDir);
		$this->setTitle($title);

		$source = new Source\Source($path, $this->src, "Components");
		$this->addSource($source);

		if (isset($_GET['ref'])) {
			$this->setRef($_GET['ref']);
		}
		if (isset($_GET['src'])) {
			$this->setSrc($_GET['src']);
		}
	}


	/**
	 * @param string $ref
	 */
	public function setRef($ref)
	{
		$this->ref = $ref;
	}

	/**
	 * @return string
	 */
	public function getRef()
	{
		return $this->ref;
	}

	/**
	 * @param string $src
	 */
	public function setSrc($src)
	{
		$this->src = $src;
	}

	/**
	 * @return string
	 */
	public function getSrc($src)
	{
		return $this->src;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{	
		return $this->title;
	}

	public function getSources()
	{
		return $this->sources;
	}

	public function getSource()
	{
		return $this->sources[$this->src];
	}

	/**
	 * Add Components from a Frontend Framework to the styleguide (such as twitter bootstrap)
	 * 
	 * @var \Thopra\Styleguide\Source\SourceInterface $source
	 */
	public function addSource($source)
	{
		$this->sources[$source->getKey()] = $source;
	}

	/**
	 * Remove source
	 *
	 * @var \Thopra\Styleguide\Source\SourceInterface $source
	 */
	public function removeSource($source)
	{
		unset($this->sources[$source->getName()]);
	}

	/**
	 * sets the template Dir
	 * @var string $dir
	 */
	public function setTemplateDir($dir)
	{
		$this->templateDir = $dir;
	}

	/**
	 * sets the template Dir
	 * @return string 
	 */
	public function getTemplateDir($dir)
	{	
		return $this->templateDir;
	}


	/**
	 * Renders the styleguide
	 *
	 * @return void
	 */
	public function render()
	{
		if ($this->ref) {
			$this->displayReference($this->sources[$this->src], $this->ref);
		} else {
			$this->displayTemplate( 'Layout/Styleguide', array('template' => 'Index'));
		}
	}

	/**
	 * Renders a Template within Styleguide::templateDir
	 * 
	 * @var string $templateName
	 * @var array $vars
	 */
	public function displayTemplate($templateName, $vars = array())
	{
		$templateName = $templateName.'.phtml';
		$templateName = $this->getAbsTemplatePath($templateName);
		$Styleguide = $this;

		include($templateName);
	}

	/**
	 * Displays a Section of the styleguide
	 *
	 * @var \Thopra\Styleguide\Source\SourceInterface
	 * @var string $reference
	 */
	public function displayReference($source, $reference) {
		try {
	        $section = $source->getParser()->getSection($reference);

	        $this->displayTemplate( 	'Layout/Styleguide', 
							        	array(
							        		'template' => 'Reference',
							        		'section' => $section
							        	)
							        );

	    } catch (UnexpectedValueException $e) {
	        $this->displayTemplate( 'Layout/Styleguide', 
	        						array(
	        							'template' => 'Index'
	        						)
	        					);
	    }
	}
	

	/**
	 * @param string $templateName
	 */
	private function getAbsTemplatePath($templateName)
	{
		if (!file_exists($this->getAbsTemplateDir($this->templateDir).DIRECTORY_SEPARATOR.$templateName)) {
			if (!file_exists($this->getAbsTemplateDir($this->defaultTemplateDir).DIRECTORY_SEPARATOR.$templateName)) {
				throw new \Exception("Template not found: ".$this->getAbsTemplateDir($this->defaultTemplateDir).DIRECTORY_SEPARATOR.$templateName, 1);
			} else {
				return $this->getAbsTemplateDir($this->defaultTemplateDir).DIRECTORY_SEPARATOR.$templateName;
			}
		}
		return $this->getAbsTemplateDir($this->templateDir).DIRECTORY_SEPARATOR.$templateName;
	}

	/**
	 * @param string $dir
	 */
	private function getAbsTemplateDir($dir)
	{
		if(is_dir(realpath($dir))) {
			return realpath($dir);
		}

		$dir = dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.$dir;
		if(is_dir($dir)) {
			return $dir;
		}

		throw new \Exception("Template Directory does not exist: ".$dir, 1);
	}



}