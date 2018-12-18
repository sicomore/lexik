<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
// use Symfony\Component\Serializer\Serializer;
// use Symfony\Component\Serializer\Encoder\CsvEncoder;
// use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class ExportCsvCommand extends Command
{
  protected static $defaultName = 'app:export-csv';
  private $entityManager;


  public function __construct(EntityManagerInterface $em)
  {
    parent::__construct();
    $this->entityManager = $em;
  }

  protected function configure()
  {
    $this
    ->setDescription('Generation of an entity in CSV format')
    ->setHelp('Please : provide an entity name (ex: "Product") as the argument of the command.')
    ->addArgument('entity', InputArgument::REQUIRED, 'The entity to export.')
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $io = new SymfonyStyle($input, $output);

    $output->writeln([
      'CSV file generation launched ...',
      '============',
    ]);

    $entity = $input->getArgument('entity');
    $entityFullName = 'App\\Entity\\' . $entity;

    try {
      $liste = $this->entityManager->getRepository($entityFullName)->findAll();

    } catch (\Exception $e) {
      $io->error($e->getMessage());
    }

    if (isset($liste)) {
      $listeNormalised = [];
      $fileName = lcfirst($entity) . '-list-export.csv';
      $handle = fopen('./' .$fileName, 'w+');
      $or = new \ReflectionObject($liste[0]);
      $fields = $or->getProperties();
      $thead = [];
      foreach ($fields as $field) {
        $thead[] = $field->getName();
      }
      fputcsv($handle, $thead);

      foreach ($liste as $item) {
        $or = new \ReflectionObject($item);
        $values = [];
        foreach ($or->getProperties() as $prop) {
          $prop->setAccessible(true);
          $value = $prop->getValue($item);
          if ($value instanceof \DateTime) {
            $value = $value->format('d/m/Y H:i:s');
          }
          $values[] = $value;
          $prop->setAccessible(false);
        }
        fputcsv($handle, $values);
      }

      fclose($handle);

      // $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
      // $results = $serializer->serialize($listeProduits, 'csv');

      $io->success('Your CSV file has been successfully created and its name is "'. $fileName . '"');
    }
  }
}
