<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;

use EzSystems\EzPlatformRest\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Output\Generator;
use EzSystems\EzPlatformRest\Output\Visitor;
use eZ\Publish\API\Repository\Values\Content\Relation as RelationValue;

/**
 * RestRelation value object visitor.
 */
class RestRelation extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \EzSystems\EzPlatformRest\Output\Visitor $visitor
     * @param \EzSystems\EzPlatformRest\Output\Generator $generator
     * @param \EzSystems\EzPlatformRest\Server\Values\RestRelation $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $generator->startObjectElement('Relation');
        $visitor->setHeader('Content-Type', $generator->getMediaType('Relation'));

        $generator->startAttribute(
            'href',
            $this->router->generate(
                'ezpublish_rest_loadVersionRelation',
                [
                    'contentId' => $data->contentId,
                    'versionNumber' => $data->versionNo,
                    'relationId' => $data->relation->id,
                ]
            )
        );
        $generator->endAttribute('href');

        $generator->startObjectElement('SourceContent', 'ContentInfo');
        $generator->startAttribute(
            'href',
            $this->router->generate(
                'ezpublish_rest_loadContent',
                [
                    'contentId' => $data->contentId,
                ]
            )
        );
        $generator->endAttribute('href');
        $generator->endObjectElement('SourceContent');

        $generator->startObjectElement('DestinationContent', 'ContentInfo');
        $generator->startAttribute(
            'href',
            $this->router->generate(
                'ezpublish_rest_loadContent',
                [
                    'contentId' => $data->relation->getDestinationContentInfo()->id,
                ]
            )
        );
        $generator->endAttribute('href');
        $generator->endObjectElement('DestinationContent');

        if ($data->relation->sourceFieldDefinitionIdentifier !== null) {
            $generator->startValueElement('SourceFieldDefinitionIdentifier', $data->relation->sourceFieldDefinitionIdentifier);
            $generator->endValueElement('SourceFieldDefinitionIdentifier');
        }

        $generator->startValueElement('RelationType', $this->getRelationTypeString($data->relation->type));
        $generator->endValueElement('RelationType');

        $generator->endObjectElement('Relation');
    }

    /**
     * Returns $relationType as a readable string.
     *
     * @param int $relationType
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function getRelationTypeString($relationType)
    {
        $relationTypeList = [];

        if (RelationValue::COMMON & $relationType) {
            $relationTypeList[] = 'COMMON';
        }
        if (RelationValue::EMBED & $relationType) {
            $relationTypeList[] = 'EMBED';
        }
        if (RelationValue::LINK & $relationType) {
            $relationTypeList[] = 'LINK';
        }
        if (RelationValue::FIELD & $relationType) {
            $relationTypeList[] = 'ATTRIBUTE';
        }
        if (RelationValue::ASSET & $relationType) {
            $relationTypeList[] = 'ASSET';
        }

        if (empty($relationTypeList)) {
            throw new \Exception('Unknown relation type ' . $relationType . '.');
        }

        return implode(',', $relationTypeList);
    }
}
