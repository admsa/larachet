<?php namespace Admsa\Larachet\Library;

use Illuminate\Filesystem\Filesystem;

class JsRenderer {

    /**
     * Javascript scripts directory.
     *
     * @var string
     */
    protected $dir;

    /**
     * Script tag constant
     *
     * @var string
     */
    const TAG = "<script src=\"%s\"></script>\n\t";

    /**
     * Holds filesystem object
     *
     * @var Illuminate\Filesystem\FileSystem;
     */
    protected $filesystem;

    /**
     * JsRenderer constructor
     *
     * @param Illuminate\Filesystem\Filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->dir = __DIR__ . '/../Resources/js/';
    }

    /**
     * Get javascript contents
     *
     * @return string
     */
    public function getContents()
    {
        $content = null;
        foreach ($this->filesystem->allFiles(__DIR__ . '/../Resources/js/') as $file) {
            $content .= $file->getContents() . "\n";
        }
        return $content;
    }

    /**
     * Render script
     *
     * @var string $route
     * @return string
     */
    public function renderScript($route)
    {
        return sprintf(static::TAG, route($route));
    }
}
