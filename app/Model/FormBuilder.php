<?php 
namespace Rhef\Model;

class FormBuilder
{
    public function fields() {
        
        $fields = [];
        $fields = [
            [
                'label' => esc_html__( 'General Field', 'easyform' ),
                'desc' => esc_html__( 'General Field', 'easyform' ),
                'icon' => 'G',
                'fields' => [
                    [
                        'label' => esc_html__( 'Text', 'easyform' ),
                        'desc' => esc_html__( 'Text Field', 'easyform' ),
                        'icon' => 'T',
                        'type' => 'text',
                    ],
                    [
                        'label' => esc_html__( 'Number', 'easyform' ),
                        'desc' => esc_html__( 'Number Field', 'easyform' ),
                        'icon' => 'N',
                        'type' => 'number',
                    ],
                    [
                        'label' => esc_html__( 'Email', 'easyform' ),
                        'desc' => esc_html__( 'Email Field', 'easyform' ),
                        'icon' => 'E',
                        'type' => 'email',
                    ],
                    [
                        'label' => esc_html__( 'Password', 'easyform' ),
                        'desc' => esc_html__( 'Password Field', 'easyform' ),
                        'icon' => 'P',
                        'type' => 'password',
                    ]
                ]
            ],
            [
                'label' => esc_html__( 'Advance Field', 'easyform' ),
                'desc' => esc_html__( 'Advance Field', 'easyform' ),
                'icon' => 'G',
                'fields' => [
                    [
                        'label' => esc_html__( 'Heading', 'easyform' ),
                        'desc' => esc_html__( 'Heading Field', 'easyform' ),
                        'icon' => 'H',
                        'type' => 'heading',
                    ],
                    [
                        'label' => esc_html__( 'Paragraph', 'easyform' ),
                        'desc' => esc_html__( 'Paragraph Field', 'easyform' ),
                        'icon' => 'P',
                        'type' => 'paragraph',
                    ]                    
                ]
            ]
        ];
        return apply_filters('rhef_form_fields', $fields );;
    }

}
