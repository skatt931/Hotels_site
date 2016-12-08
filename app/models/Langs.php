<?php

class Langs extends PrfModel
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $lang;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var integer
     */
    public $currency;

    /**
     *
     * @var integer
     */
    public $def;

    /**
     *
     * @var integer
     */
    public $trash;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('currency', 'TgCurrencys', 'id', array('alias' => 'TgCurrencys'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Langs[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Langs
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    static function setVars($lang) { //Встановити всі системні параметри
        if (!isset(Hl::$l) || Hl::$l != $lang) {
            Hl::$l = $lang;
            $config = Hl::$di->get("config");
            Hl::$di->get("dispatcher")->setParam('language', $lang);
            if ($lang == $config->languages->emptyLang)
                Hl::$url->setStaticBaseUri(Hl::$url->getBaseUri());
            else
                Hl::$url->setStaticBaseUri($lang . Hl::$url->getBaseUri());
            if ($lang == $config->languages->emptyPostfix)
                $lang_postfix = '';
            else
                $lang_postfix = '_' . $lang;

            Hl::$pstfx = $lang_postfix;
            Hl::$name = 'name' . $lang_postfix;

            Hl::$aCurURL = explode('/', trim($_SERVER["REQUEST_URI"], '/'));
            if (strlen(Hl::$aCurURL[0]) == 2)
                array_shift(Hl::$aCurURL);
        }
    }

    static function check($lang) {
        $aLangs = Langs::load();
        if (isset($aLangs[$lang]))
            return $lang;
        else
            return Hl::$di->get('session')->get("languageDef");
    }

    static function load($reload=false) {
        $session = Hl::$di->get('session');
        if ($reload || !$session->has("Langs")) {

            $mLangs = HT::sel('langs');
            $aLangs = [];

            foreach ($mLangs as $l) {
                $aLangs[$l['lang']] = ['name' => $l['name'], 'currency' => $l['currency'],
                  'def' => $l['def'], 'trash' => $l['trash'] ];
                if ($l['def'] == 1)
                    $session->set("languageDef", $l['lang'] );
            }
            unset($mLangs);
            $session->set("Langs", $aLangs);
            return $aLangs;
        }
        else
            return $session->get("Langs");
    }

}
