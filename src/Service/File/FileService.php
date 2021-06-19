<?php


namespace App\Service\File;


use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FileService
{
    public const ENTERPRISE_AVATAR = 'avatar';

    private FilesystemOperator $defaultStorage;
    private LoggerInterface $logger;

    public function __construct(FilesystemOperator $defaultStorage, LoggerInterface $logger)
    {
        $this->defaultStorage = $defaultStorage;
        $this->logger = $logger;
    }

    /**
     * @throws FilesystemException
     */
    public function uploadFile(UploadedFile $file, string $prefix, string $visibility): string
    {
        $fileName = sprintf('%s/%s.%s', $prefix, sha1(uniqid()), $file->guessExtension());

        $this->defaultStorage->writeStream(
            $fileName,
            fopen($file->getPathname(), 'r'),
            ['visibility' => $visibility]
        );

        return $fileName;
    }

    public function validateFile(Request $request, string $inputName): UploadedFile
    {
        if(null === $file = $request->files->get($inputName)){
            throw new BadRequestHttpException(sprintf('Cannot get file with input name %s', $inputName));
        }

        return $file;
    }

    public function deleteFile(?string $path): void
    {
        try {
            if (null !== $path) {
                $this->defaultStorage->delete($path);
            }
        } catch (FilesystemException $e) {
            $this->logger->warning(sprintf('File %s not found in storage', $path));
        }
    }
}