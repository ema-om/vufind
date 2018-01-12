<?php
/**
 * Created by IntelliJ IDEA.
 * User: boehm
 * Date: 12.12.17
 * Time: 15:26
 */

namespace ElasticSearch\VuFind\RecordDriver;


class ESPerson extends ElasticSearch
{
    /**
     * @method getAbstract()
     * @method getBirthPlace()
     * TODO Possibly rather date than string
     * @method  getBirthYear()
     * @method getDeathPlace()
     * TODO Possibly rather date than string
     * @method getDeathYear()
     * @method getGenre()
     * @method getInfluenced()
     * @method getInfluencedBy()
     * @method getMovement()
     * @method getNationality()
     * @method getNotableWork()
     * @method getOccupation()
     * @method getPartner()
     * @method getSpouse()
     * @method getThumbnail()
     * @method getBirthPlaceDisplayField()
     * @method getGenreDisplayField()
     * @method getInfluencedDisplayField()
     * @method getInfluencedByDisplayField()
     * @method getMovementDisplayField()
     * @method getNationalityDisplayField()
     * @method getNotableWorkDisplayField()
     * @method getOccupationDisplayField()
     * @method getPartnerDisplayField()
     * @method getSpouseDisplayField()
     * @method getOccupationDisplayField()
     *
     * @param $name
     * @param $arguments
     * @return array|null
     */
    public function __call(string $name, $arguments)
    {
        if ($pos = strpos($name, "DisplayField")) {
            $fieldName = substr($name, 3, $pos);
            $field = $this->getField(sprintf('dbp%sAsLiteral', $fieldName), 'lsb');

            return !is_null($field)
                ? $this->getValueByLanguagePriority($field)
                : null;
        }

        $fieldName = lcfirst(substr($name, 3));
        return $this->getField($fieldName);
    }

    public function getPersonId()
    {
        return $this->getField('id', '@', '');
    }

    public function getFirstName()
    {
        return $this->getField('firstName', 'foaf');
    }

    public function getLastName()
    {
        return $this->getField('lastName', 'foaf');
    }

    public function getName()
    {
        return $this->getField('label', 'rdfs');
    }

    /**
     * @return \DateTime|null
     */
    public function getBirthDate()
    {
        $date = $this->getField('birthDate');
        return $this->extractDate($date);
    }

    public function getAbstract()
    {
        $abstract = $this->getField('abstract');
        $localizedAbstract = $this->getValueByLanguagePriority($abstract);
        return is_array($localizedAbstract) && count($localizedAbstract) > 0 ? $localizedAbstract[0] : null;
    }

    public function getPseudonym()
    {
        $pseudonym = $this->getField("pseudonym");
        return $this->getValueByLanguagePriority($pseudonym);
    }

    public function getBirthPlaceDisplayField()
    {
        $place = $this->getField("dbpBirthPlaceAsLiteral", "lsb");
        return $this->getValueByLanguagePriority($place);
    }

    public function getDeathPlaceDisplayField()
    {
        $place = $this->getField("dbpDeathPlaceAsLiteral", "lsb");
        return $this->getValueByLanguagePriority($place);
    }

    /**
     * @return \DateTime|null
     */
    public function getDeathDate()
    {
        $date = $this->getField("deathDate");
        return $this->extractDate($date);
    }

    public function getSameAs()
    {
        return $this->getField("sameAs", "owl");
    }

    public function getRdfType()
    {
        return $this->getField("type", "rdf");
    }

    /**
     * Should be more than firstName, lastName, label
     * @return bool
     */
    public function hasSufficientData() : bool
    {
        $count = count($this->fields["_source"]);
        return $count > 3;
    }

    // TODO
    /*
     * "rdfs:label": {
            "type": "text"
          },
          "schema:alternateName": {
            "type": "text"
          },
          "schema:birthDate": {
            "type": "date",
            "format": "year"
          },
          "schema:deathDate": {
            "type": "date",
            "format": "year"
          },
          "schema:familyName": {
            "type": "text"
          },
          "schema:gender": {
            "type": "keyword"
          },
          "schema:givenName": {
            "type": "text"
          },
          "schema:sameAs": {
            "type": "keyword"
          },
          "skos:note": {
            "type": "text"
          }
     */


    /**
     * @param $date
     * @return \DateTime|null
     */
    protected function extractDate($date)
    {
        if ($date !== null) {
            return new \DateTime($date);
        }
        return null;
    }

    /**
     * @param string $name
     * @param string $prefix
     * @param string $delimiter
     * @return array|null
     */
    protected function getField(string $name, string $prefix = "dbp", string $delimiter = ":")
    {
        return parent::getField($name, $prefix, $delimiter);
    }


    public function getAllFields() {
        return $this->fields;
    }
}
