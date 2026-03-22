<?php

namespace Drupal\footnote_writer\Plugin\Filter;

use Drupal\Component\Utility\Html;
use Drupal\filter\Plugin\FilterBase;
use Drupal\filter\FilterProcessResult;

/**
 * Provides a [fn: ...] footnotes filter.
 *
 * @Filter(
 *   id = "filter_footnotes",
 *   title = @Translation("Footnotes ([fn: ...])"),
 *   description = @Translation("Converts [fn: ...] into numbered footnotes appended to the end."),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_TRANSFORM_IRREVERSIBLE
 * )
 */
final class FootnotesFilter extends FilterBase {

  /**
   * {@inheritdoc}
   */
  public function process($text, $langcode) {
    if ($text === '' || strpos($text, '[fn:') === FALSE) {
      return new FilterProcessResult($text);
    }

    $footnotes = [];
    $counter = 1;

    // Non-greedy match; does not cross closing bracket.
    $pattern = '/\[fn:\s*(.*?)\]/s';

    $processed = preg_replace_callback($pattern, function ($m) use (&$footnotes, &$counter) {
      $content = $m[1];

      // Store raw content; do not escape here. Let text format decide safety.
      $footnotes[$counter] = $content;

      $ref_id = 'fn-ref-' . $counter;
      $fn_id  = 'fn-' . $counter;

      $sup = '<sup id="' . $ref_id . '" class="footnote-ref">'
        . '<a href="#' . $fn_id . '" rel="footnote">' . $counter . '</a>'
        . '</sup>';

      $counter++;
      return $sup;
    }, $text);

    if (!empty($footnotes)) {
      $processed .= $this->buildFootnoteBlock($footnotes);
    }

    $result = new FilterProcessResult($processed);


    return $result;
  }

  /**
   * Builds the footnotes block HTML.
   */
  private function buildFootnoteBlock(array $footnotes): string {
    $out  = '<div class="footnotes">';
    $out .= '<hr />';
    $out .= '<ol class="footnotes-list">';

    foreach ($footnotes as $i => $content) {
      $fn_id  = 'fn-' . $i;
      $ref_id = 'fn-ref-' . $i;

      // Back-reference link.
      $back = ' <a href="#' . $ref_id . '" class="footnote-backref" aria-label="Back to reference">↩</a>';

      $out .= '<li id="' . $fn_id . '" class="footnote-item">';
      $out .= $content . $back;
      $out .= '</li>';
    }

    $out .= '</ol></div>';

    return $out;
  }

  /**
   * {@inheritdoc}
   */
  public function tips($long = FALSE) {
    if ($long) {
      return $this->t('Use [fn: text] to add a footnote. Example: "Claim[fn: source]".');
    }
    return $this->t('Add footnotes with [fn: ...].');
  }

}
