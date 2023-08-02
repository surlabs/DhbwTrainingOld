<?php

/**
 * Class xdhtExporter
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

class ilDhbwTrainingExporter extends ilXmlExporter
{

    /**
     * #SUR# return type definition
     * @return void
     */
    public function init(): void
    {
        // TODO: Implement init() method.
    }

    /**
     * #SUR# return type definition
     * @param $a_entity
     * @param $a_schema_version
     * @param $a_id
     * @return string
     */
    public function getXmlRepresentation($a_entity, $a_schema_version, $a_id): string
    {

        $xml = '';

        $writer = new xdhtSettingsXmlWriter();
        //ilUtil::makeDirParents($this->getAbsoluteExportDirectory());
        //$writer->setFileTargetDirectories($this->getRelativeExportDirectory(), $this->getAbsoluteExportDirectory());
        $writer->start();
        $xml .= $writer->getXml();

        return $xml;
    }

    /**
     * #SUR# return type definition
     * @param $a_entity
     * @return array
     */
    public function getValidSchemaVersions($a_entity): array
    {
        return array(
            "5.2.0" => array(
                "namespace"    => "http://www.ilias.de/Modules/HTMLLearningModule/htlm/5_2",
                "xsd_file"     => "ilias_htlm_5_2.xsd",
                "uses_dataset" => true,
                "min"          => "5.2.0",
                "max"          => ""
            )
        );
    }
}