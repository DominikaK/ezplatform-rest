<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Server\Input\Parser;

use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinitionCreateStruct;
use eZ\Publish\Core\Repository\ContentTypeService;
use eZ\Publish\Core\Repository\Values\ContentType\ContentTypeCreateStruct;
use EzSystems\EzPlatformRest\Server\Input\Parser\FieldDefinitionCreate;
use EzSystems\EzPlatformRest\Server\Input\Parser\ContentTypeCreate;

class ContentTypeCreateTest extends BaseTest
{
    /**
     * Tests the ContentTypeCreate parser.
     */
    public function testParse()
    {
        $inputArray = $this->getInputArray();

        $contentTypeCreate = $this->getParser();
        $result = $contentTypeCreate->parse($inputArray, $this->getParsingDispatcherMock());

        $this->assertInstanceOf(
            ContentTypeCreateStruct::class,
            $result,
            'ContentTypeCreateStruct not created correctly.'
        );

        $this->assertEquals(
            'new_content_type',
            $result->identifier,
            'identifier not created correctly'
        );

        $this->assertEquals(
            'eng-US',
            $result->mainLanguageCode,
            'mainLanguageCode not created correctly'
        );

        $this->assertEquals(
            'remote123456',
            $result->remoteId,
            'remoteId not created correctly'
        );

        $this->assertEquals(
            '<title>',
            $result->urlAliasSchema,
            'urlAliasSchema not created correctly'
        );

        $this->assertEquals(
            '<title>',
            $result->nameSchema,
            'nameSchema not created correctly'
        );

        $this->assertTrue(
            $result->isContainer,
            'isContainer not created correctly'
        );

        $this->assertEquals(
            Location::SORT_FIELD_PATH,
            $result->defaultSortField,
            'defaultSortField not created correctly'
        );

        $this->assertEquals(
            Location::SORT_ORDER_ASC,
            $result->defaultSortOrder,
            'defaultSortOrder not created correctly'
        );

        $this->assertTrue(
            $result->defaultAlwaysAvailable,
            'defaultAlwaysAvailable not created correctly'
        );

        $this->assertEquals(
            ['eng-US' => 'New content type'],
            $result->names,
            'names not created correctly'
        );

        $this->assertEquals(
            ['eng-US' => 'New content type description'],
            $result->descriptions,
            'descriptions not created correctly'
        );

        $this->assertEquals(
            new \DateTime('2012-12-31T12:30:00'),
            $result->creationDate,
            'creationDate not created correctly'
        );

        $this->assertEquals(
            14,
            $result->creatorId,
            'creatorId not created correctly'
        );

        foreach ($result->fieldDefinitions as $fieldDefinition) {
            $this->assertInstanceOf(
                FieldDefinitionCreateStruct::class,
                $fieldDefinition,
                'ContentTypeCreateStruct field definition not created correctly.'
            );
        }
    }

    /**
     * Test ContentTypeCreate parser throwing exception on missing identifier.
     *
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     * @expectedExceptionMessage Missing 'identifier' element for ContentTypeCreate.
     */
    public function testParseExceptionOnMissingIdentifier()
    {
        $inputArray = $this->getInputArray();
        unset($inputArray['identifier']);

        $contentTypeCreate = $this->getParser();
        $contentTypeCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test ContentTypeCreate parser throwing exception on missing mainLanguageCode.
     *
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     * @expectedExceptionMessage Missing 'mainLanguageCode' element for ContentTypeCreate.
     */
    public function testParseExceptionOnMissingMainLanguageCode()
    {
        $inputArray = $this->getInputArray();
        unset($inputArray['mainLanguageCode']);

        $contentTypeCreate = $this->getParser();
        $contentTypeCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test ContentTypeCreate parser throwing exception on invalid names.
     *
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     * @expectedExceptionMessage Invalid 'names' element for ContentTypeCreate.
     */
    public function testParseExceptionOnInvalidNames()
    {
        $inputArray = $this->getInputArray();
        unset($inputArray['names']['value']);

        $contentTypeCreate = $this->getParser();
        $contentTypeCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test ContentTypeCreate parser throwing exception on invalid descriptions.
     *
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     * @expectedExceptionMessage Invalid 'descriptions' element for ContentTypeCreate.
     */
    public function testParseExceptionOnInvalidDescriptions()
    {
        $inputArray = $this->getInputArray();
        unset($inputArray['descriptions']['value']);

        $contentTypeCreate = $this->getParser();
        $contentTypeCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test ContentTypeCreate parser throwing exception on invalid User.
     *
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     * @expectedExceptionMessage Missing '_href' attribute for User element in ContentTypeCreate.
     */
    public function testParseExceptionOnInvalidUser()
    {
        $inputArray = $this->getInputArray();
        unset($inputArray['User']['_href']);

        $contentTypeCreate = $this->getParser();
        $contentTypeCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test ContentTypeCreate parser throwing exception on invalid FieldDefinitions.
     *
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     * @expectedExceptionMessage Invalid 'FieldDefinitions' element for ContentTypeCreate.
     */
    public function testParseExceptionOnInvalidFieldDefinitions()
    {
        $inputArray = $this->getInputArray();
        unset($inputArray['FieldDefinitions']['FieldDefinition']);

        $contentTypeCreate = $this->getParser();
        $contentTypeCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test ContentTypeCreate parser throwing exception on invalid FieldDefinitions.
     *
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     * @expectedExceptionMessage ContentTypeCreate should provide at least one field definition.
     */
    public function testParseExceptionOnMissingFieldDefinitions()
    {
        $inputArray = $this->getInputArray();
        // Field definitions are required only with immediate publish
        $inputArray['__publish'] = true;
        $inputArray['FieldDefinitions']['FieldDefinition'] = [];

        $contentTypeCreate = $this->getParser();
        $contentTypeCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test ContentTypeCreate parser throwing exception on invalid FieldDefinitions.
     *
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     * @expectedExceptionMessage Invalid 'FieldDefinition' element for ContentTypeCreate.
     */
    public function testParseExceptionOnInvalidFieldDefinition()
    {
        $inputArray = $this->getInputArray();
        $inputArray['FieldDefinitions']['FieldDefinition'] = ['hi there'];

        $contentTypeCreate = $this->getParser();
        $contentTypeCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Returns the ContentTypeCreate parser.
     *
     * @return \EzSystems\EzPlatformRest\Server\Input\Parser\ContentTypeCreate
     */
    protected function internalGetParser()
    {
        return new ContentTypeCreate(
            $this->getContentTypeServiceMock(),
            $this->getFieldDefinitionCreateParserMock(),
            $this->getParserTools()
        );
    }

    /**
     * Returns the FieldDefinitionCreate parser mock object.
     *
     * @return \EzSystems\EzPlatformRest\Server\Input\Parser\FieldDefinitionCreate
     */
    private function getFieldDefinitionCreateParserMock()
    {
        $fieldDefinitionCreateParserMock = $this->createMock(FieldDefinitionCreate::class);

        $fieldDefinitionCreateParserMock->expects($this->any())
            ->method('parse')
            ->with([], $this->getParsingDispatcherMock())
            ->willReturn(new FieldDefinitionCreateStruct());

        return $fieldDefinitionCreateParserMock;
    }

    /**
     * Get the content type service mock object.
     *
     * @return \eZ\Publish\API\Repository\ContentTypeService
     */
    protected function getContentTypeServiceMock()
    {
        $contentTypeServiceMock = $this->createMock(ContentTypeService::class);

        $contentTypeServiceMock->expects($this->any())
            ->method('newContentTypeCreateStruct')
            ->with($this->equalTo('new_content_type'))
            ->willReturn(
                    new ContentTypeCreateStruct(
                        [
                            'identifier' => 'new_content_type',
                        ]
                    )
            );

        return $contentTypeServiceMock;
    }

    /**
     * Returns the array under test.
     *
     * @return array
     */
    protected function getInputArray()
    {
        return [
            'identifier' => 'new_content_type',
            'mainLanguageCode' => 'eng-US',
            'remoteId' => 'remote123456',
            'urlAliasSchema' => '<title>',
            'nameSchema' => '<title>',
            'isContainer' => 'true',
            'defaultSortField' => 'PATH',
            'defaultSortOrder' => 'ASC',
            'defaultAlwaysAvailable' => 'true',
            'names' => [
                'value' => [
                    [
                        '_languageCode' => 'eng-US',
                        '#text' => 'New content type',
                    ],
                ],
            ],
            'descriptions' => [
                'value' => [
                    [
                        '_languageCode' => 'eng-US',
                        '#text' => 'New content type description',
                    ],
                ],
            ],
            'modificationDate' => '2012-12-31T12:30:00',
            'User' => [
                '_href' => '/user/users/14',
            ],

            // mocked
            'FieldDefinitions' => [
                'FieldDefinition' => [
                    [],
                    [],
                ],
            ],
        ];
    }

    public function getParseHrefExpectationsMap()
    {
        return [
            ['/user/users/14', 'userId', 14],
        ];
    }
}
