<?php

namespace App\Command;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


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
    ->setDescription('Génération de la liste des produits au format CSV')
    ->setHelp('Cette commande vous sert à exporter la liste de produits disponibles dans un fichier CSV.')
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $io = new SymfonyStyle($input, $output);
    
    $output->writeln([
      'Génération de fichier CSV lancée ...',
      '============',
    ]);

    // $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
    $listeProduits = $this->entityManager->getRepository(Produit::class)->findBy([], ['nom' => 'ASC']);

    $listeNormalised = [];
    $handle = fopen('./liste-des-produits-export.csv', 'w+');
    $or = new \ReflectionObject($listeProduits[0]);
    $fields = $or->getProperties();
    $thead = [];
    foreach ($fields as $field) {
      $thead[] = $field->getName();
    }
    fputcsv($handle, $thead);

    foreach ($listeProduits as $produit) {
      $or = new \ReflectionObject($produit);
      $values = [];
      foreach ($or->getProperties() as $prop) {
        $prop->setAccessible(true);
        $value = $prop->getValue($produit);
        if ($value instanceof \DateTime) {
          $value = $value->format('d/m/Y H:i:s');
        }
        $values[] = $value;
        $prop->setAccessible(false);
      }
      fputcsv($handle, $values);
    }

    fclose($handle);

    // $results = $serializer->serialize($listeProduits, 'csv');

    $io->success('Votre fichier CSV a été généré avec succès et se nomme "liste-des-produits-export.csv"');
  }
}
